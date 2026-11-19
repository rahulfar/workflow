import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { getSessionUser } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { Cpu, Terminal, CheckCircle2, ShieldCheck, Copy } from 'lucide-react';

export default async function McpPage() {
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
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-black text-slate-900">Model Context Protocol (MCP)</h1>
              <p className="text-sm text-slate-500 mt-1">Connect Claude Desktop, Cursor, or AI agents directly to WorkFlow Pro</p>
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div className="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                  <Terminal className="w-5 h-5" />
                </div>
                <div>
                  <h3 className="font-bold text-slate-800">MCP JSON-RPC Endpoint</h3>
                  <p className="text-xs text-slate-500">Live SSE & HTTP POST protocol endpoint</p>
                </div>
              </div>

              <div className="p-4 bg-slate-900 rounded-xl text-xs font-mono text-indigo-300 break-all select-all">
                /api/mcp
              </div>

              <div className="space-y-2 text-xs text-slate-600">
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                  <span>Tools: <code>get_team_status</code>, <code>get_employee_tasks</code>, <code>verify_shift_status</code></span>
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                  <span>Resources: <code>workflow://team/live</code>, <code>workflow://office/ip-whitelist</code></span>
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-500" />
                  <span>Prompts: <code>daily_standup_summary</code>, <code>attendance_audit</code></span>
                </div>
              </div>
            </div>

            <div className="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                  <ShieldCheck className="w-5 h-5" />
                </div>
                <div>
                  <h3 className="font-bold text-slate-800">Claude Desktop & Agent Config</h3>
                  <p className="text-xs text-slate-500">Paste into <code>claude_desktop_config.json</code></p>
                </div>
              </div>

              <pre className="p-4 bg-slate-900 rounded-xl text-xs font-mono text-emerald-400 overflow-x-auto">
{`{
  "mcpServers": {
    "workflow-pro": {
      "url": "http://localhost:3000/api/mcp"
    }
  }
}`}
              </pre>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
