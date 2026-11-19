import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/mongodb';
import AuthorizedIp from '@/models/AuthorizedIp';
import { getSession } from '@/lib/auth';
import { getClientIp, checkClientIpAuthorized } from '@/lib/ip-utils';

export async function GET() {
  try {
    const session = await getSession();
    if (!session || session.role !== 'admin') {
      return NextResponse.json({ success: false, message: 'Forbidden' }, { status: 403 });
    }

    await connectToDatabase();
    const currentIp = await getClientIp();
    const currentCheck = await checkClientIpAuthorized(currentIp);
    const ips = await AuthorizedIp.find().sort({ _id: -1 }).lean();

    return NextResponse.json({
      success: true,
      data: {
        current_ip: currentIp,
        is_current_authorized: currentCheck.authorized,
        current_location: currentCheck.location,
        ips,
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

    const newRule = await AuthorizedIp.create({
      ip_address: body.ip_address,
      location: body.location,
      notes: body.notes,
      status: body.status || 'active',
    });

    return NextResponse.json({
      success: true,
      message: 'IP address whitelisted successfully',
      data: newRule,
    });
  } catch (err: any) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}
