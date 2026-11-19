import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { Calendar, Clock, Wifi, ShieldCheck, CheckCircle } from 'lucide-react';

export default async function AttendancePage() {
  const user = await getSession();

  if (!user) {
    redirect('/login');
  }

  // Pre-loaded recent attendance logs
  const logs = [
    { id: 325, employee: 'Kartikeyan', date: '2026-11-19', clockIn: '09:42 AM', clockOut: 'In Progress', hours: '3h 35m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 324, employee: 'Mrinali', date: '2026-11-19', clockIn: '09:38 AM', clockOut: 'In Progress', hours: '3h 39m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 323, employee: 'Udit', date: '2026-11-19', clockIn: '09:45 AM', clockOut: 'In Progress', hours: '3h 32m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 322, employee: 'Rahul', date: '2026-11-19', clockIn: '09:35 AM', clockOut: 'In Progress', hours: '3h 42m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 321, employee: 'Uvaish', date: '2026-11-19', clockIn: '09:40 AM', clockOut: 'In Progress', hours: '3h 37m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 320, employee: 'Kartikeyan', date: '2026-11-18', clockIn: '09:41 AM', clockOut: '06:35 PM', hours: '8h 54m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 319, employee: 'Udit', date: '2026-11-18', clockIn: '09:44 AM', clockOut: '06:31 PM', hours: '8h 47m', ip: '192.168.1.22', status: 'present', verified: true },
    { id: 318, employee: 'Mrinali', date: '2026-11-18', clockIn: '09:39 AM', clockOut: '06:40 PM', hours: '9h 01m', ip: '192.168.1.22', status: 'present', verified: true },
  ];

  return (
    <div className="min-h-screen flex bg-slate-50">
      <Sidebar user={user} />
      <div className="flex-1 flex flex-col min-w-0">
        <Navbar user={user} />
        
        <main className="p-4 sm:p-6 lg:p-8 space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-black text-slate-900">Attendance & Shifts</h1>
              <p className="text-sm text-slate-500 mt-1">Verified office Wi-Fi shift logs with IP geofence validation</p>
            </div>
          </div>

          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                  <tr>
                    <th className="py-3.5 px-4 sm:px-6">Staff Member</th>
                    <th className="py-3.5 px-4">Date</th>
                    <th className="py-3.5 px-4">Clock-In</th>
                    <th className="py-3.5 px-4">Clock-Out</th>
                    <th className="py-3.5 px-4">Total Duration</th>
                    <th className="py-3.5 px-4">Wi-Fi IP</th>
                    <th className="py-3.5 px-4 text-center">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {logs.map((log) => (
                    <tr key={log.id} className="hover:bg-slate-50/60 transition-colors">
                      <td className="py-3.5 px-4 sm:px-6 font-bold text-slate-800">
                        {log.employee}
                      </td>
                      <td className="py-3.5 px-4 text-slate-600 font-mono text-xs">
                        {log.date}
                      </td>
                      <td className="py-3.5 px-4 font-semibold text-slate-700">
                        {log.clockIn}
                      </td>
                      <td className="py-3.5 px-4 font-semibold text-slate-700">
                        {log.clockOut}
                      </td>
                      <td className="py-3.5 px-4 text-indigo-600 font-bold">
                        {log.hours}
                      </td>
                      <td className="py-3.5 px-4">
                        <span className="inline-flex items-center gap-1 font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                          <Wifi className="w-3 h-3 text-emerald-500" />
                          {log.ip}
                        </span>
                      </td>
                      <td className="py-3.5 px-4 text-center">
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <CheckCircle className="w-3 h-3" />
                          Verified
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
