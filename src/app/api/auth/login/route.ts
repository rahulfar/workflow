import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/mongodb';
import User from '@/models/User';
import { verifyPassword, createSession } from '@/lib/auth';
import { ensureUsersSeeded, getStaticSeedData } from '@/lib/seed';

export async function POST(req: Request) {
  try {
    const body = await req.json();
    const role = (body.role || body.selected_role || '').toLowerCase();
    const username = body.username || body.email;
    const password = body.password;

    if (!role || !username || !password) {
      return NextResponse.json(
        { success: false, message: 'Please provide role, username/email, and password.' },
        { status: 400 }
      );
    }

    const cleanUsername = String(username).trim().toLowerCase();
    let user: any = null;

    try {
      await connectToDatabase();
      await ensureUsersSeeded();

      user = await User.findOne({
        $or: [
          { email: cleanUsername },
          { username: cleanUsername },
          { name: new RegExp(`^${cleanUsername}$`, 'i') },
        ],
        status: 'active',
      });
    } catch (dbErr: any) {
      console.warn('MongoDB query warning, using bundled seed data fallback:', dbErr?.message);
    }

    // Fallback to static bundled seed data if user wasn't found in DB
    if (!user) {
      try {
        const seedData = getStaticSeedData();
        const match = (seedData.users || []).find((u: any) =>
          (u.email?.toLowerCase() === cleanUsername ||
            u.username?.toLowerCase() === cleanUsername ||
            u.name?.toLowerCase() === cleanUsername) &&
          u.status === 'active'
        );
        if (match) {
          const roleId = Number(match.role_id);
          const roleCategory =
            roleId === 1
              ? 'admin'
              : roleId === 2
              ? 'manager'
              : roleId === 3
              ? 'product_manager'
              : 'employee';
          user = {
            _id: match.id || 1,
            sql_id: match.id,
            name: match.name,
            email: match.email,
            role_category: roleCategory,
            role_title: match.designation || 'Staff',
            department_name: 'General',
            avatar: match.avatar,
            password: match.password || match.password_hash,
          };
        }
      } catch (seedErr) {
        console.error('Seed fallback error:', seedErr);
      }
    }

    if (!user) {
      return NextResponse.json(
        { success: false, message: 'Invalid username or password.' },
        { status: 401 }
      );
    }

    // Role category guard
    const userRole =
      user.role_category ||
      (user.role_id === 1
        ? 'admin'
        : user.role_id === 2
        ? 'manager'
        : user.role_id === 3
        ? 'product_manager'
        : 'employee');

    // Allow admin to access any role portal, otherwise ensure role matches
    if (userRole !== 'admin' && userRole !== role) {
      return NextResponse.json(
        {
          success: false,
          message: `This account is assigned to the ${userRole
            .replace('_', ' ')
            .toUpperCase()} role. Please select "${userRole
            .replace('_', ' ')
            .toUpperCase()}" on the login card to sign in.`,
        },
        { status: 403 }
      );
    }

    // Validate password
    const isPasswordValid = verifyPassword(password, user.password);
    if (!isPasswordValid) {
      return NextResponse.json(
        { success: false, message: 'Invalid username or password.' },
        { status: 401 }
      );
    }

    // Determine dashboard redirect
    let redirect = '/employee/dashboard';
    if (userRole === 'admin') redirect = '/admin/dashboard';
    else if (userRole === 'manager') redirect = '/manager/dashboard';
    else if (userRole === 'product_manager') redirect = '/product-manager/dashboard';

    const token = await createSession({
      id: user._id.toString(),
      sql_id: user.sql_id,
      name: user.name,
      email: user.email,
      role: userRole,
      role_title: user.role_title || user.designation || 'Staff',
      department_name: user.department_name,
      avatar: user.avatar,
    });

    const response = NextResponse.json({
      success: true,
      message: 'Login successful!',
      data: {
        redirect,
        token,
        user: {
          name: user.name,
          email: user.email,
          role: userRole,
        },
      },
    });

    const cookieOpts = {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax' as const,
      maxAge: 7 * 24 * 60 * 60,
      path: '/',
    };
    response.cookies.set('workflow_token', token, cookieOpts);
    response.cookies.set('auth_token', token, cookieOpts);

    return response;
  } catch (err: any) {
    console.error('Login error:', err);
    return NextResponse.json(
      { success: false, message: err.message || 'Internal server error' },
      { status: 500 }
    );
  }
}
