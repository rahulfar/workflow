import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { CheckCircle, Clock, Calendar, Smartphone, ArrowUpRight, Award } from 'lucide-react';

export default async function EmployeeDashboardPage() {
  const user = await getSession();

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
          <div className="p-6 rounded-3xl bg-gradient-to-r from-purple-950 via-indigo-900 to-slate-900 text-white shadow-xl relative overflow-hidden">
            <div className="relative z-10">
              <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur-md mb-3 inline-block">
                Staff & Employee Workspace
              </span>
              <h1 className="text-2xl sm:text-3xl font-black">
                My Workday & Shift Dashboard
              </h1>
              <p className="text-purple-200 text-sm mt-1 max-w-xl">
                Track assigned tasks, monitor personal workday hours, and verify your office Wi-Fi shift status.
              </p>
            </div>
          </div>

          {/* Metric Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">My Tasks</div>
                <div className="text-2xl font-black text-slate-900 mt-1">4</div>
                <div className="text-xs text-purple-600 font-semibold mt-1">Active assignments</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                <CheckCircle className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Scheduled Shift</div>
                <div className="text-xl font-black text-slate-900 mt-1">09:40 - 18:30</div>
                <div className="text-xs text-indigo-600 font-semibold mt-1">Fixed Office Hours</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <Clock className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Days Present</div>
                <div className="text-2xl font-black text-slate-900 mt-1">22</div>
                <div className="text-xs text-emerald-600 font-semibold mt-1">This Month</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <Calendar className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Performance</div>
                <div className="text-2xl font-black text-slate-900 mt-1">98%</div>
                <div className="text-xs text-amber-600 font-semibold mt-1">On-time Completion</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <Award className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Quick Actions */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="/mobile-shift" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-purple-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Touch Wi-Fi Shift Clock-In</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-purple-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Start or End shift via verified office Wi-Fi network (192.168.1.22).</p>
            </a>

            <a href="/tasks" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-purple-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">My Task Queue</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-purple-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Update status, log hours, and view project requirements.</p>
            </a>
          </div>
        </main>
      </div>
    </div>
  );
}
