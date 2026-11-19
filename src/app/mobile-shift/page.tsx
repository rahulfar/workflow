'use client';

import React, { useEffect, useState } from 'react';
import { Wifi, WifiOff, Play, Square, Clock, ShieldCheck, CheckCircle2, AlertTriangle, ArrowLeft } from 'lucide-react';
import Link from 'next/link';

export default function MobileShiftPage() {
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

  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);

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
      console.error(e);
    }
  };

  useEffect(() => {
    checkStatus();
    const interval = setInterval(checkStatus, 15000);
    return () => clearInterval(interval);
  }, []);

  const handleShiftAction = async (action: 'clock_in' | 'clock_out') => {
    setErrorMsg(null);
    setSuccessMsg(null);

    if (!ipStatus?.allowed) {
      setErrorMsg(`Office Wi-Fi required: Your current IP (${ipStatus?.clientIp}) is not an authorized office Wi-Fi.`);
      return;
    }

    setLoading(true);
    try {
      const res = await fetch('/api/attendance', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action }),
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Operation failed');
      }

      setSuccessMsg(action === 'clock_in' ? 'Shift started successfully! Welcome to work.' : 'Shift completed! Have a great evening.');
      await checkStatus();
    } catch (err: any) {
      setErrorMsg(err.message || 'Network error occurred');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-900 text-white flex flex-col justify-between p-4 sm:p-6 lg:p-8">
      {/* Top Bar */}
      <div className="flex items-center justify-between">
        <Link href="/dashboard" className="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
          <ArrowLeft className="w-4 h-4" />
          <span>Dashboard</span>
        </Link>
        <span className="text-xs font-mono text-indigo-400 bg-indigo-950/60 border border-indigo-800/60 px-3 py-1 rounded-full">
          Touch Shift Clock
        </span>
      </div>

      {/* Main Touch Card */}
      <div className="max-w-md w-full mx-auto my-auto space-y-6">
        {/* Wi-Fi Verification Card */}
        <div className={`p-5 rounded-3xl border transition-all ${
          ipStatus?.allowed 
            ? 'bg-emerald-950/30 border-emerald-500/40 text-emerald-200' 
            : 'bg-amber-950/30 border-amber-500/40 text-amber-200'
        }`}>
          <div className="flex items-center gap-3">
            {ipStatus?.allowed ? (
              <div className="w-10 h-10 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                <Wifi className="w-5 h-5 animate-pulse" />
              </div>
            ) : (
              <div className="w-10 h-10 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                <WifiOff className="w-5 h-5" />
              </div>
            )}
            <div>
              <div className="font-bold text-sm text-white">
                {ipStatus?.allowed ? 'Authorized Office Wi-Fi' : 'Wi-Fi Geofence Restricted'}
              </div>
              <div className="text-xs font-mono opacity-80">
                IP: {ipStatus?.clientIp || 'Detecting...'}
              </div>
            </div>
          </div>
          <div className="mt-3 text-xs leading-relaxed opacity-90 border-t border-white/10 pt-2">
            {ipStatus?.allowed
              ? 'Connected to office network (192.168.1.22 / authorized subnets). Shift controls unlocked.'
              : 'Please connect to Krafting Digital office Wi-Fi to start or end your work shift.'}
          </div>
        </div>

        {/* Status Feedback */}
        {errorMsg && (
          <div className="p-4 rounded-2xl bg-red-950/50 border border-red-500/40 text-red-200 text-xs flex items-center gap-2">
            <AlertTriangle className="w-4 h-4 flex-shrink-0" />
            <span>{errorMsg}</span>
          </div>
        )}

        {successMsg && (
          <div className="p-4 rounded-2xl bg-emerald-950/50 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-2">
            <CheckCircle2 className="w-4 h-4 flex-shrink-0" />
            <span>{successMsg}</span>
          </div>
        )}

        {/* Current State */}
        <div className="text-center py-4">
          <div className="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
            Current Shift State
          </div>
          <div className="text-3xl font-black text-white">
            {shiftStatus?.inShift ? (
              <span className="text-emerald-400 flex items-center justify-center gap-2">
                <span className="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></span>
                ACTIVE SHIFT
              </span>
            ) : (
              <span className="text-slate-400">OFF SHIFT</span>
            )}
          </div>
          {shiftStatus?.clockInTime && (
            <div className="text-xs text-slate-400 mt-2 font-mono">
              Clocked in at {shiftStatus.clockInTime}
            </div>
          )}
        </div>

        {/* Large Shift Action Buttons */}
        <div className="space-y-4">
          {!shiftStatus?.inShift ? (
            <button
              type="button"
              disabled={loading || !ipStatus?.allowed}
              onClick={() => handleShiftAction('clock_in')}
              className="w-full py-6 px-6 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xl shadow-xl shadow-emerald-600/30 transition-all flex items-center justify-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed group active:scale-95"
            >
              <Play className="w-6 h-6 fill-white" />
              <span>START SHIFT</span>
            </button>
          ) : (
            <button
              type="button"
              disabled={loading || !ipStatus?.allowed}
              onClick={() => handleShiftAction('clock_out')}
              className="w-full py-6 px-6 rounded-3xl bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white font-black text-xl shadow-xl shadow-rose-600/30 transition-all flex items-center justify-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed group active:scale-95"
            >
              <Square className="w-6 h-6 fill-white" />
              <span>END SHIFT</span>
            </button>
          )}
        </div>
      </div>

      {/* Footer policy */}
      <div className="text-center text-xs text-slate-500 py-2">
        Office Shift Policy: 09:40 AM - 06:30 PM • Fixed Office Hours
      </div>
    </div>
  );
}
