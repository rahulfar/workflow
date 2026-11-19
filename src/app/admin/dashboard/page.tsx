import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { Users, CheckCircle, Clock, ShieldCheck, ArrowUpRight, Activity } from 'lucide-react';

export default async function AdminDashboardPage() {
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
          <div className="p-6 rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white shadow-xl relative overflow-hidden">
            <div className="relative z-10">
              <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur-md mb-3 inline-block">
                Admin Executive Workspace
              </span>
              <h1 className="text-2xl sm:text-3xl font-black">
                System Overview & Workspace Health
              </h1>
              <p className="text-indigo-200 text-sm mt-1 max-w-xl">
                Real-time visibility across all 4 roles, authorized Wi-Fi network endpoints, active shifts, and tasks.
              </p>
            </div>
          </div>

          {/* Metric Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Active Staff</div>
                <div className="text-2xl font-black text-slate-900 mt-1">14</div>
                <div className="text-xs text-emerald-600 font-semibold mt-1">100% Onboarded</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <Users className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Live Tasks</div>
                <div className="text-2xl font-black text-slate-900 mt-1">26</div>
                <div className="text-xs text-indigo-600 font-semibold mt-1">Across 4 Projects</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <CheckCircle className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Departments</div>
                <div className="text-2xl font-black text-slate-900 mt-1">10</div>
                <div className="text-xs text-amber-600 font-semibold mt-1">Operational</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <Activity className="w-6 h-6" />
              </div>
            </div>

            <div className="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
              <div>
                <div className="text-xs font-bold uppercase tracking-wider text-slate-500">Office Wi-Fi IP</div>
                <div className="text-2xl font-black text-slate-900 mt-1">13</div>
                <div className="text-xs text-emerald-600 font-semibold mt-1">192.168.1.22 Active</div>
              </div>
              <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <ShieldCheck className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Quick Management Actions */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/employees" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Employee Management</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">View and manage staff accounts, roles, and shift profiles.</p>
            </a>

            <a href="/tasks" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Task Kanban Board</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Track pending, in-progress, and completed deliverable tasks.</p>
            </a>

            <a href="/ip-settings" className="p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-500 hover:shadow-md transition-all group">
              <div className="flex items-center justify-between mb-2">
                <span className="font-bold text-slate-800">Office Wi-Fi Geofence</span>
                <ArrowUpRight className="w-4 h-4 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
              </div>
              <p className="text-xs text-slate-500">Configure allowed Wi-Fi IP addresses (192.168.1.22) for shift buttons.</p>
            </a>
          </div>
        </main>
      </div>
    </div>
  );
}
