import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { ShieldCheck, Plus, Wifi, Trash2, CheckCircle2 } from 'lucide-react';

export default async function IpSettingsPage() {
  const user = await getSession();

  if (!user || (user.role !== 'admin' && user.role !== 'manager')) {
    redirect('/dashboard');
  }

  // Pre-loaded whitelist IPs including the user-specified 192.168.1.22
  const authorizedIps = [
    { id: 1, ip: '192.168.1.22', label: 'Primary Office Wi-Fi (Required)', status: 'active', created: '2026-11-19' },
    { id: 2, ip: '192.168.29.174', label: 'Office Main Gateway Fiber', status: 'active', created: '2026-10-15' },
    { id: 3, ip: '192.168.1.*', label: 'Office Local Subnet Whitelist', status: 'active', created: '2026-11-19' },
    { id: 4, ip: '127.0.0.1', label: 'Localhost Loopback (Dev)', status: 'active', created: '2026-08-19' },
    { id: 5, ip: '::1', label: 'IPv6 Loopback (Dev)', status: 'active', created: '2026-08-19' },
  ];

  return (
    <div className="min-h-screen flex bg-slate-50">
      <Sidebar user={user} />
      <div className="flex-1 flex flex-col min-w-0">
        <Navbar user={user} />
        
        <main className="p-4 sm:p-6 lg:p-8 space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-black text-slate-900">Office Wi-Fi IP Whitelist</h1>
              <p className="text-sm text-slate-500 mt-1">Configure verified office IP addresses for shift start and end geofencing</p>
            </div>
          </div>

          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="p-5 border-b border-slate-100 flex items-center justify-between">
              <div>
                <h3 className="font-bold text-slate-800">Authorized IP Addresses</h3>
                <p className="text-xs text-slate-500">Only devices on these Wi-Fi networks will display the shift action buttons</p>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                  <tr>
                    <th className="py-3.5 px-4 sm:px-6">IP Address / CIDR</th>
                    <th className="py-3.5 px-4">Network Label</th>
                    <th className="py-3.5 px-4">Date Added</th>
                    <th className="py-3.5 px-4 text-center">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {authorizedIps.map((row) => (
                    <tr key={row.id} className="hover:bg-slate-50/60 transition-colors">
                      <td className="py-3.5 px-4 sm:px-6 font-mono font-bold text-indigo-700">
                        <div className="flex items-center gap-2">
                          <Wifi className="w-4 h-4 text-emerald-500" />
                          <span>{row.ip}</span>
                        </div>
                      </td>
                      <td className="py-3.5 px-4 font-medium text-slate-700">
                        {row.label}
                      </td>
                      <td className="py-3.5 px-4 font-mono text-xs text-slate-500">
                        {row.created}
                      </td>
                      <td className="py-3.5 px-4 text-center">
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <CheckCircle2 className="w-3 h-3" />
                          Active
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
