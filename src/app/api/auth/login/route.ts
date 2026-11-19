import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/mongodb';
import User from '@/models/User';
import { verifyPassword, createSession } from '@/lib/auth';

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

      // Auto-seed initial users if MongoDB collection is empty
      const userCount = await User.countDocuments();
      if (userCount === 0) {
        console.log('MongoDB users collection is empty. Auto-seeding from database/mongodb_seed_data.json...');
        try {
          const fs = await import('fs');
          const path = await import('path');
          const seedPath = path.join(process.cwd(), 'database', 'mongodb_seed_data.json');
          if (fs.existsSync(seedPath)) {
            const raw = fs.readFileSync(seedPath, 'utf8');
            const seedData = JSON.parse(raw);
            const seedUsers = (seedData.users || []).map((u: any) => {
              const roleId = Number(u.role_id);
              const roleCategory = roleId === 1 ? 'admin' : (roleId === 2 ? 'manager' : (roleId === 3 ? 'product_manager' : 'employee'));
              return {
                sql_id: u.id,
                name: u.name,
                email: u.email?.toLowerCase().trim(),
                username: u.username?.toLowerCase().trim(),
                password: u.password || u.password_hash,
                role_id: roleId,
                role_category: roleCategory,
                role_title: u.designation || 'Staff',
                department_id: u.department_id || 1,
                department_name: 'Engineering',
                employee_code: u.employee_code,
                designation: u.designation,
                shift_timing: u.shift_timing || '09:40 AM - 06:30 PM (Fixed Office)',
                avatar: u.avatar || 'default_avatar.png',
                phone: u.phone,
                status: u.status || 'active',
              };
            });
            if (seedUsers.length > 0) {
              await User.insertMany(seedUsers);
              console.log(`Auto-seeded ${seedUsers.length} users into MongoDB!`);
            }
          }
        } catch (seedErr) {
          console.error('Auto-seed error:', seedErr);
        }
      }

      user = await User.findOne({
        $or: [
          { email: cleanUsername },
          { username: cleanUsername },
          { name: new RegExp(`^${cleanUsername}$`, 'i') },
        ],
        status: 'active',
      });
    } catch (dbErr: any) {
      console.warn('MongoDB connecting fallback to database seed file:', dbErr?.message);
      // Fallback to database seed file if remote MongoDB is pending configuration
      try {
        const fs = await import('fs');
        const path = await import('path');
        const seedPath = path.join(process.cwd(), 'database', 'mongodb_seed_data.json');
        if (fs.existsSync(seedPath)) {
          const raw = fs.readFileSync(seedPath, 'utf8');
          const seedData = JSON.parse(raw);
          const match = (seedData.users || []).find((u: any) =>
            (u.email?.toLowerCase() === cleanUsername || u.username?.toLowerCase() === cleanUsername || u.name?.toLowerCase() === cleanUsername) &&
            u.status === 'active'
          );
          if (match) {
            const roleId = Number(match.role_id);
            const roleCategory = roleId === 1 ? 'admin' : (roleId === 2 ? 'manager' : (roleId === 3 ? 'product_manager' : 'employee'));
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
    const userRole = user.role_category || (user.role_id === 1 ? 'admin' : (user.role_id === 2 ? 'manager' : (user.role_id === 3 ? 'product_manager' : 'employee')));
    
    // Allow admin to access any role portal, otherwise ensure role matches
    if (userRole !== 'admin' && userRole !== role) {
      return NextResponse.json(
        { 
          success: false, 
          message: `This account is assigned to the ${userRole.replace('_', ' ').toUpperCase()} role. Please select "${userRole.replace('_', ' ').toUpperCase()}" on the login card to sign in.` 
        },
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
    if (userRole === 'admin') redirect = '/admin/dashboard';
    else if (userRole === 'manager') redirect = '/manager/dashboard';
    else if (userRole === 'product_manager') redirect = '/product-manager/dashboard';

    await createSession({
      id: user._id.toString(),
      sql_id: user.sql_id,
      name: user.name,
      email: user.email,
      role: userRole,
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
          role: userRole,
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
