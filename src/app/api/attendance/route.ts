import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/mongodb';
import Attendance from '@/models/Attendance';
import { getSession } from '@/lib/auth';
import { checkClientIpAuthorized, getClientIp } from '@/lib/ip-utils';

export async function GET(req: Request) {
  try {
    const url = new URL(req.url);
    const action = url.searchParams.get('action') || 'status';
    const queryIp = url.searchParams.get('ip') || url.searchParams.get('client_ip');

    const clientIp = queryIp || (await getClientIp());
    const ipAuth = await checkClientIpAuthorized(clientIp);

    // If only checking Wi-Fi IP authorization
    if (action === 'check_ip') {
      return NextResponse.json({
        success: true,
        allowed: ipAuth.authorized,
        client_ip: clientIp,
        message: ipAuth.authorized
          ? 'Connected to authorized office Wi-Fi.'
          : `Restricted: IP ${clientIp} is not in authorized office networks (e.g. 192.168.1.22).`,
      });
    }

    const session = await getSession();
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    let record: any = null;
    try {
      await connectToDatabase();
      const today = new Date().toISOString().split('T')[0];
      const userId = session.sql_id || 1;
      record = await Attendance.findOne({ user_id: userId, date: today }).lean();
    } catch (e) {
      // Graceful fallback if database connecting
    }

    const inShift = Boolean(record && record.status === 'running' && !record.clock_out);
    let todayHours = '0h 0m';
    if (record && record.total_seconds) {
      const h = Math.floor(record.total_seconds / 3600);
      const m = Math.floor((record.total_seconds % 3600) / 60);
      todayHours = `${h}h ${m}m`;
    }

    return NextResponse.json({
      success: true,
      allowed: ipAuth.authorized,
      client_ip: clientIp,
      data: {
        in_shift: inShift,
        clock_in_time: record?.clock_in,
        today_hours: todayHours,
        is_ip_authorized: ipAuth.authorized,
        client_ip: clientIp,
        location: ipAuth.location,
        has_active_shift: inShift,
        has_completed_shift: Boolean(record?.clock_out),
        record,
      },
    });
  } catch (err: any) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}

export async function POST(req: Request) {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ success: false, message: 'Unauthorized' }, { status: 401 });
    }

    await connectToDatabase();
    const clientIp = await getClientIp();
    const ipAuth = await checkClientIpAuthorized(clientIp);

    // SECURITY CHECK: Verify Client IP against authorized whitelist
    if (!ipAuth.authorized) {
      return NextResponse.json(
        {
          success: false,
          message: `Access Denied: You must be connected to an authorized office Wi-Fi network. Detected IP: ${clientIp}`,
        },
        { status: 403 }
      );
    }

    const body = await req.json();
    const action = body.action || 'clock_in';
    const today = new Date().toISOString().split('T')[0];
    const nowTime = new Date().toTimeString().split(' ')[0]; // HH:MM:SS
    const userId = session.sql_id || 1;

    if (action === 'clock_in') {
      const existing = await Attendance.findOne({ user_id: userId, date: today });
      if (existing) {
        if (existing.status === 'running') {
          return NextResponse.json(
            { success: false, message: 'You already have an active shift today.' },
            { status: 400 }
          );
        }
        return NextResponse.json(
          { success: false, message: 'Shift already recorded for today.' },
          { status: 400 }
        );
      }

      const newRecord = await Attendance.create({
        user_id: userId,
        user_name: session.name,
        date: today,
        shift_start: '09:40:00',
        shift_end: '18:30:00',
        clock_in: nowTime,
        status: 'running',
        clock_in_ip: clientIp,
        device_type: 'Desktop/Mobile',
      });

      return NextResponse.json({
        success: true,
        message: `Shift started successfully at ${nowTime}!`,
        data: newRecord,
      });
    } else if (action === 'clock_out') {
      const activeShift = await Attendance.findOne({
        user_id: userId,
        date: today,
        status: 'running',
      });

      if (!activeShift) {
        return NextResponse.json(
          { success: false, message: 'No active shift found to clock out from.' },
          { status: 400 }
        );
      }

      // Calculate total duration
      let totalSeconds = 0;
      if (activeShift.clock_in) {
        const [inH, inM, inS] = activeShift.clock_in.split(':').map(Number);
        const [outH, outM, outS] = nowTime.split(':').map(Number);
        const inSec = inH * 3600 + inM * 60 + inS;
        const outSec = outH * 3600 + outM * 60 + outS;
        totalSeconds = Math.max(0, outSec - inSec);
      }

      const hoursWorked = totalSeconds / 3600;
      const finalStatus = hoursWorked >= 8.5 ? 'present' : hoursWorked >= 4.5 ? 'half_day' : 'present';

      activeShift.clock_out = nowTime;
      activeShift.clock_out_ip = clientIp;
      activeShift.total_seconds = totalSeconds;
      activeShift.status = finalStatus as any;
      await activeShift.save();

      const hoursFmt = Math.floor(totalSeconds / 3600);
      const minsFmt = Math.floor((totalSeconds % 3600) / 60);

      return NextResponse.json({
        success: true,
        message: `Shift ended successfully at ${nowTime}! Worked: ${hoursFmt}h ${minsFmt}m.`,
        data: activeShift,
      });
    }

    return NextResponse.json({ success: false, message: 'Invalid shift action.' }, { status: 400 });
  } catch (err: any) {
    return NextResponse.json({ success: false, message: err.message }, { status: 500 });
  }
}
