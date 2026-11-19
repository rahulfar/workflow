import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/mongodb';
import User from '@/models/User';
import Department from '@/models/Department';
import Role from '@/models/Role';
import { getSession } from '@/lib/auth';

export async function GET(req: Request) {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    await connectToDatabase();
    const { searchParams } = new URL(req.url);
    const q = searchParams.get('q') || '';
    const deptId = searchParams.get('department_id');
    const status = searchParams.get('status');

    const filter: any = {};
    if (q) {
      filter.$or = [
        { name: { $regex: q, $options: 'i' } },
        { email: { $regex: q, $options: 'i' } },
        { designation: { $regex: q, $options: 'i' } },
        { employee_code: { $regex: q, $options: 'i' } },
      ];
    }
    if (deptId && Number(deptId) > 0) {
      filter.department_id = Number(deptId);
    }
    if (status) {
      filter.status = status;
    }

    const employees = await User.find(filter).sort({ sql_id: 1, _id: 1 }).lean();
    const departments = await Department.find().sort({ name: 1 }).lean();
    const roles = await Role.find().sort({ id: 1 }).lean();

    const counts = {
      total: await User.countDocuments(),
      active: await User.countDocuments({ status: 'active' }),
      inactive: await User.countDocuments({ status: 'inactive' }),
    };

    return NextResponse.json({
      success: true,
      data: {
        employees,
        departments,
        roles,
        counts,
      },
    });
  } catch (err: any) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}

export async function POST(req: Request) {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ success: false, message: 'Forbidden' }, { status: 403 });
    }

    await connectToDatabase();
    const body = await req.json();

    const highest = await User.findOne().sort({ sql_id: -1 }).select('sql_id').lean();
    const newSqlId = (highest?.sql_id || 0) + 1;

    const newEmp = await User.create({
      ...body,
      sql_id: newSqlId,
      status: 'active',
      employee_code: body.employee_code || `EMP-${String(newSqlId).padStart(3, '0')}`,
    });

    return NextResponse.json({
      success: true,
      message: 'Employee created successfully!',
      data: newEmp,
    });
  } catch (err: any) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}
