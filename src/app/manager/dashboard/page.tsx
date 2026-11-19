import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { getSessionUser } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { Users, CheckCircle, Calendar, ShieldCheck, ArrowUpRight, Clock } from 'lucide-react';

export default async function ManagerDashboardPage() {
  const cookieStore = await cookies();
  const token = cookieStore.get('auth_token')?.value;
  const user = token ? await getSessionUser(token) : null;

  if (!user) {
    redirect('/login');
  }

  return (
    <div className="min-h-screen flex bg-slate-50">
      <Sidebar user={user} />
      <div className="flex-1 flex flex-col min-w-0">
        <Navbar user={user} />
        
        <main className="p-4 sm:p-6 lg:p-8 space-y-6">
          {/* Header Banner */}
          <div className="p-6 rounded-3xl bg-gradient-to-r from-emerald-900 via-teal-800 to-slate-900 text-white shadow-xl relative overflow-hidden">
            <div className="relative z-10">
              <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur-md mb-3 inline-block">
                Operations & Management Workspace
              </span>
              <h1 className="text-2xl sm:text-3xl font-black">
                Team Attendance & Operations
              </h1>
              <p className="text-emerald-200 text-sm mt-1 max-w-xl">
                Oversee department teams, verify staff attendance logs, and monitor active office Wi-Fi shift statuses.
              </p>
            </div>
          </div>

          {/* Metric Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Team Size</div>
                <div className="text-2xl font-black text-slate-900 mt-1">14</div>
                <div className="text-xs text-emerald-600 font-semibold mt-1">Staff active</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <Users className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Shift Verified</div>
                <div className="text-2xl font-black text-slate-900 mt-1">100%</div>
                <div className="text-xs text-indigo-600 font-semibold mt-1">Office Wi-Fi Validated</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <ShieldCheck className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Attendance Records</div>
                <div className="text-2xl font-black text-slate-900 mt-1">325</div>
                <div className="text-xs text-amber-600 font-semibold mt-1">Logged shifts</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <Calendar className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Fixed Shift</div>
                <div className="text-xl font-black text-slate-900 mt-1">09:40 - 18:30</div>
                <div className="text-xs text-slate-500 font-semibold mt-1">Office Policy</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                <Clock className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Quick Links */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="/attendance" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Attendance & Shifts Roster</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Review monthly attendance logs, shift durations, and Wi-Fi IP validation history.</p>
            </a>

            <a href="/employees" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Staff & Roles Directory</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Access full employee directory with designated roles and contact channels.</p>
            </a>
          </div>
        </main>
      </div>
    </div>
  );
}
