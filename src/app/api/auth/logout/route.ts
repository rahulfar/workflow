import { NextResponse } from 'next/server';
import { destroySession, getSession } from '@/lib/auth';

export async function POST() {
  await destroySession();
  return NextResponse.json({
    success: true,
    message: 'Logged out successfully',
  });
}

export async function GET() {
  await destroySession();
  return NextResponse.redirect(new URL('/login?logged_out=1', process.env.NEXT_PUBLIC_APP_URL || 'http://localhost:3000'));
}
