import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { Briefcase, CheckCircle, Clock, AlertTriangle, ArrowUpRight, FolderKanban } from 'lucide-react';

export default async function ProductManagerDashboardPage() {
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
          <div className="p-6 rounded-3xl bg-gradient-to-r from-amber-950 via-orange-900 to-slate-900 text-white shadow-xl relative overflow-hidden">
            <div className="relative z-10">
              <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur-md mb-3 inline-block">
                Product & Sprint Management
              </span>
              <h1 className="text-2xl sm:text-3xl font-black">
                Product Roadmap & Task Deliverables
              </h1>
              <p className="text-orange-200 text-sm mt-1 max-w-xl">
                Sprint status, live feature developments, priority tasks, and team deliverables across active projects.
              </p>
            </div>
          </div>

          {/* Metric Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Total Tasks</div>
                <div className="text-2xl font-black text-slate-900 mt-1">26</div>
                <div className="text-xs text-amber-600 font-semibold mt-1">4 Active Projects</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <FolderKanban className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">In Progress</div>
                <div className="text-2xl font-black text-slate-900 mt-1">8</div>
                <div className="text-xs text-indigo-600 font-semibold mt-1">Development Phase</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <Clock className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Completed</div>
                <div className="text-2xl font-black text-slate-900 mt-1">14</div>
                <div className="text-xs text-emerald-600 font-semibold mt-1">Sprint Delivered</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <CheckCircle className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Flagged / Urgent</div>
                <div className="text-2xl font-black text-slate-900 mt-1">2</div>
                <div className="text-xs text-rose-600 font-semibold mt-1">Needs Attention</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <AlertTriangle className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Quick Actions */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="/tasks" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-amber-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Sprint Kanban Board</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-amber-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Manage sprint columns: Backlog, In Progress, In Review, and Done.</p>
            </a>

            <a href="/attendance" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-amber-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Team Workday Status</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-amber-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Check team active hours, Wi-Fi shift status, and daily progress logs.</p>
            </a>
          </div>
        </main>
      </div>
    </div>
  );
}
