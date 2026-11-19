import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { CheckSquare, Clock, AlertCircle, Plus, Filter, Tag } from 'lucide-react';

export default async function TasksPage() {
  const user = await getSession();

  if (!user) {
    redirect('/login');
  }

  // Pre-loaded task list from database seed
  const tasks = [
    { id: 1, title: 'Build Next.js 15 App Architecture', project: 'Workflow Modernization', status: 'completed', priority: 'high', assignee: 'Rahul' },
    { id: 2, title: 'MongoDB Mongoose Schema & Indexing', project: 'Database Migration', status: 'completed', priority: 'high', assignee: 'Rahul' },
    { id: 3, title: 'Verify Office Wi-Fi Geofence IP (192.168.1.22)', project: 'Shift Security', status: 'in_progress', priority: 'critical', assignee: 'Kartikeyan' },
    { id: 4, title: 'Sprint 14 Product Feature Deliverables', project: 'Product Roadmap', status: 'in_progress', priority: 'medium', assignee: 'Udit' },
    { id: 5, title: 'Monthly Operations & Attendance Audit', project: 'HR Management', status: 'in_progress', priority: 'medium', assignee: 'Mrinali' },
    { id: 6, title: 'Design System & Tailwind Color Palette', project: 'Design & UI/UX', status: 'completed', priority: 'low', assignee: 'Sarthak' },
    { id: 7, title: 'SEO Organic Search Traffic Optimization', project: 'Marketing Growth', status: 'pending', priority: 'medium', assignee: 'Uvaish' },
    { id: 8, title: 'Vercel Deployment & Secret Provisioning', project: 'DevOps & Cloud', status: 'in_progress', priority: 'high', assignee: 'Kartikeyan' },
  ];

  const columns = [
    { id: 'pending', label: 'Pending / Backlog', color: 'border-slate-300' },
    { id: 'in_progress', label: 'In Progress', color: 'border-indigo-500' },
    { id: 'completed', label: 'Completed', color: 'border-emerald-500' },
  ];

  return (
    <div className="min-h-screen flex bg-slate-50">
      <Sidebar user={user} />
      <div className="flex-1 flex flex-col min-w-0">
        <Navbar user={user} />
        
        <main className="p-4 sm:p-6 lg:p-8 space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-black text-slate-900">Tasks & Projects Kanban</h1>
              <p className="text-sm text-slate-500 mt-1">Track deliverables, sprints, and assigned team responsibilities</p>
            </div>
          </div>

          {/* Kanban Columns */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {columns.map((col) => {
              const colTasks = tasks.filter(t => t.status === col.id);

              return (
                <div key={col.id} className="bg-slate-100/70 rounded-2xl p-4 border border-slate-200">
                  <div className="flex items-center justify-between mb-4 px-1">
                    <div className="flex items-center gap-2">
                      <span className={`w-3 h-3 rounded-full border-2 ${col.color} bg-white`}></span>
                      <h3 className="font-bold text-sm text-slate-800">{col.label}</h3>
                    </div>
                    <span className="px-2 py-0.5 rounded-full text-xs font-bold bg-white text-slate-600 shadow-sm">
                      {colTasks.length}
                    </span>
                  </div>

                  <div className="space-y-3">
                    {colTasks.map((task) => (
                      <div key={task.id} className="p-4 rounded-xl bg-white border border-slate-200/90 shadow-sm hover:shadow transition-shadow">
                        <div className="flex items-start justify-between gap-2 mb-2">
                          <span className="text-[11px] font-semibold text-indigo-600 uppercase tracking-wide">
                            {task.project}
                          </span>
                          <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${
                            task.priority === 'critical' ? 'bg-red-50 text-red-600 border border-red-200' :
                            task.priority === 'high' ? 'bg-amber-50 text-amber-600 border border-amber-200' :
                            'bg-slate-50 text-slate-600 border border-slate-200'
                          }`}>
                            {task.priority}
                          </span>
                        </div>
                        
                        <h4 className="font-bold text-sm text-slate-800 leading-snug mb-3">
                          {task.title}
                        </h4>

                        <div className="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                          <span className="font-medium text-slate-700">Assignee: {task.assignee}</span>
                          <span className="font-mono text-[11px]">#TASK-{task.id}</span>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              );
            })}
          </div>
        </main>
      </div>
    </div>
  );
}
