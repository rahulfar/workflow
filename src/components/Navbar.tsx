'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { Wifi, WifiOff, Play, Square, LogOut, User, Bell } from 'lucide-react';

interface NavbarProps {
  user: {
    id: number | string;
    name: string;
    email: string;
    role: string;
    avatar?: string;
  };
}

export default function Navbar({ user }: NavbarProps) {
  const router = useRouter();
  const [ipStatus, setIpStatus] = useState<{
    allowed: boolean;
    clientIp: string;
    message?: string;
  } | null>(null);

  const [shiftStatus, setShiftStatus] = useState<{
    inShift: boolean;
    clockInTime?: string;
    todayHours?: string;
  } | null>(null);

  const [actionLoading, setActionLoading] = useState(false);

  // Fetch IP authorization and current shift status
  const checkStatus = async () => {
    try {
      const [ipRes, shiftRes] = await Promise.all([
        fetch('/api/attendance?action=check_ip'),
        fetch('/api/attendance?action=status'),
      ]);

      if (ipRes.ok) {
        const ipData = await ipRes.json();
        setIpStatus({
          allowed: ipData.allowed ?? false,
          clientIp: ipData.client_ip || ipData.ip || 'Unknown',
          message: ipData.message,
        });
      }

      if (shiftRes.ok) {
        const sData = await shiftRes.json();
        setShiftStatus({
          inShift: sData.data?.in_shift ?? false,
          clockInTime: sData.data?.clock_in_time,
          todayHours: sData.data?.today_hours,
        });
      }
    } catch (e) {
      console.error('Status check error:', e);
    }
  };

  useEffect(() => {
    checkStatus();
    const interval = setInterval(checkStatus, 30000);
    return () => clearInterval(interval);
  }, []);

  const handleShiftAction = async (action: 'clock_in' | 'clock_out') => {
    if (!ipStatus?.allowed) {
      alert(`Office Wi-Fi required: Your current IP (${ipStatus?.clientIp}) is not an authorized office Wi-Fi.`);
      return;
    }

    setActionLoading(true);
    try {
      const res = await fetch('/api/attendance', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action }),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        alert(data.message || 'Action failed');
      } else {
        await checkStatus();
      }
    } catch (e: any) {
      alert('Error updating shift: ' + (e.message || 'Network error'));
    } finally {
      setActionLoading(false);
    }
  };

  const handleLogout = async () => {
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
    } catch (e) {}
    router.push('/login');
  };

  return (
    <header className="h-16 bg-white border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6">
      {/* Left Title / Greeting */}
      <div className="flex items-center gap-3">
        <h2 className="text-base sm:text-lg font-bold text-slate-800">
          Welcome, <span className="text-indigo-600">{user.name}</span>
        </h2>
        <span className="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
          {user.role.replace('_', ' ')}
        </span>
      </div>

      {/* Right Controls */}
      <div className="flex items-center gap-3 sm:gap-4">
        {/* Office Wi-Fi Indicator */}
        <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-slate-50 border border-slate-200">
          {ipStatus?.allowed ? (
            <>
              <Wifi className="w-3.5 h-3.5 text-emerald-600 animate-pulse" />
              <span className="text-emerald-700 font-semibold hidden md:inline">Office Wi-Fi Connected</span>
              <span className="text-slate-500 font-mono text-[11px]">({ipStatus.clientIp})</span>
            </>
          ) : (
            <>
              <WifiOff className="w-3.5 h-3.5 text-amber-500" />
              <span className="text-amber-700 font-medium hidden md:inline">Remote Network</span>
              <span className="text-slate-500 font-mono text-[11px]">({ipStatus?.clientIp || 'Detecting...'})</span>
            </>
          )}
        </div>

        {/* Dynamic Shift Start / Shift End Button (visible and validated by Wi-Fi IP) */}
        {ipStatus?.allowed && (
          <div>
            {!shiftStatus?.inShift ? (
              <button
                type="button"
                disabled={actionLoading}
                onClick={() => handleShiftAction('clock_in')}
                className="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm shadow-emerald-500/20 transition-all disabled:opacity-50"
              >
                <Play className="w-3.5 h-3.5 fill-white" />
                <span>Start Shift</span>
              </button>
            ) : (
              <button
                type="button"
                disabled={actionLoading}
                onClick={() => handleShiftAction('clock_out')}
                className="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm shadow-rose-500/20 transition-all disabled:opacity-50"
              >
                <Square className="w-3.5 h-3.5 fill-white" />
                <span>End Shift</span>
              </button>
            )}
          </div>
        )}

        {/* User Profile & Logout */}
        <div className="flex items-center gap-2 pl-2 border-l border-slate-200">
          <div className="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs border border-indigo-200">
            {user.name.charAt(0).toUpperCase()}
          </div>
          <button
            type="button"
            onClick={handleLogout}
            title="Sign Out"
            className="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors"
          >
            <LogOut className="w-4 h-4" />
          </button>
        </div>
      </div>
    </header>
  );
}
