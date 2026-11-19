import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/mongodb';
import User from '@/models/User';
import { verifyPassword, createSession } from '@/lib/auth';

export async function POST(req: Request) {
  try {
    const body = await req.json();
    const { role, username, password } = body;

    if (!role || !username || !password) {
      return NextResponse.json(
        { success: false, message: 'Please provide role, username/email, and password.' },
        { status: 400 }
      );
    }

    await connectToDatabase();

    const cleanUsername = String(username).trim().toLowerCase();

    const user = await User.findOne({
      $or: [
        { email: cleanUsername },
        { username: cleanUsername },
        { name: new RegExp(`^${cleanUsername}$`, 'i') },
      ],
      status: 'active',
    });

    if (!user) {
      return NextResponse.json(
        { success: false, message: 'Invalid username or password.' },
        { status: 401 }
      );
    }

    // Role category guard
    if (user.role_category !== role) {
      return NextResponse.json(
        { success: false, message: `This account does not have access to the ${role.toUpperCase()} portal.` },
        { status: 403 }
      );
    }

    // Verify password
    const isPasswordValid = verifyPassword(password, user.password || '');
    if (!isPasswordValid) {
      return NextResponse.json(
        { success: false, message: 'Invalid username or password.' },
        { status: 401 }
      );
    }

    // Determine dashboard redirect
    let redirect = '/employee/dashboard';
    if (user.role_category === 'admin') redirect = '/admin/dashboard';
    else if (user.role_category === 'manager') redirect = '/manager/dashboard';
    else if (user.role_category === 'product_manager') redirect = '/product-manager/dashboard';

    await createSession({
      id: user._id.toString(),
      sql_id: user.sql_id,
      name: user.name,
      email: user.email,
      role: user.role_category,
      role_title: user.role_title || user.designation || 'Staff',
      department_name: user.department_name,
      avatar: user.avatar,
    });

    return NextResponse.json({
      success: true,
      message: 'Login successful!',
      data: {
        redirect,
        user: {
          name: user.name,
          email: user.email,
          role: user.role_category,
        },
      },
    });
  } catch (error: any) {
    return NextResponse.json(
      { success: false, message: 'Server error: ' + error.message },
      { status: 500 }
    );
  }
}
