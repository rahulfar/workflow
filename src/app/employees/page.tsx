import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';
import Navbar from '@/components/Navbar';
import Sidebar from '@/components/Sidebar';
import { Search, Mail, Phone, Shield, Building, UserCheck } from 'lucide-react';

export default async function EmployeesPage() {
  const user = await getSession();

  if (!user) {
    redirect('/login');
  }

  // Pre-loaded employee list from SQLite / MongoDB seed
  const employees = [
    { id: 1, name: 'Kartikeyan', email: 'kartikeyan@kraftingdigital.com', code: 'KD-EMP-001', role: 'admin', designation: 'System Administrator & CEO', dept: 'Executive', status: 'active' },
    { id: 2, name: 'Mrinali', email: 'mrinali@kraftingdigital.com', code: 'KD-EMP-002', role: 'manager', designation: 'Operations Manager', dept: 'Operations', status: 'active' },
    { id: 3, name: 'Udit', email: 'contact@kraftingdigital.com', code: 'KD-EMP-003', role: 'product_manager', designation: 'Product Manager', dept: 'Product Management', status: 'active' },
    { id: 4, name: 'Rahul', email: 'finance@kraftingdigital.com', code: 'KD-EMP-004', role: 'senior_developer', designation: 'Senior Fullstack Developer', dept: 'Engineering', status: 'active' },
    { id: 5, name: 'Uvaish', email: 'uvaish@kraftingdigital.com', code: 'KD-EMP-005', role: 'seo_manager', designation: 'SEO Lead Manager', dept: 'Digital Marketing', status: 'active' },
    { id: 6, name: 'Shivanshu', email: 'shivanshu@kraftingdigital.com', code: 'KD-EMP-006', role: 'seo_executive', designation: 'Senior SEO Executive', dept: 'Digital Marketing', status: 'active' },
    { id: 7, name: 'Sarthak', email: 'sarthak@kraftingdigital.com', code: 'KD-EMP-007', role: 'graphic_designer', designation: 'Lead UI/UX Graphic Designer', dept: 'Design & Creative', status: 'active' },
    { id: 8, name: 'Priyansh', email: 'priyansh@kraftingdigital.com', code: 'KD-EMP-008', role: 'social_media_manager', designation: 'Social Media Strategist', dept: 'Marketing', status: 'active' },
    { id: 9, name: 'Abhay', email: 'abhay@kraftingdigital.com', code: 'KD-EMP-009', role: 'intern_developer', designation: 'Intern Frontend Developer', dept: 'Engineering', status: 'active' },
    { id: 10, name: 'Aman', email: 'aman@kraftingdigital.com', code: 'KD-EMP-010', role: 'intern_seo', designation: 'Intern SEO Analyst', dept: 'Digital Marketing', status: 'active' },
    { id: 11, name: 'PRABHAT', email: 'prabhat@kraftingdigital.com', code: 'KD-EMP-011', role: 'ads_expert', designation: 'Performance Ads Specialist', dept: 'Growth', status: 'active' },
    { id: 12, name: 'Anjali Singh', email: 'anjalisingh@kraftingdigital.com', code: 'KD-EMP-012', role: 'intern_seo', designation: 'Junior SEO Associate', dept: 'Digital Marketing', status: 'active' },
    { id: 13, name: 'Vicky', email: 'vicky@kraftingdigital.com', code: 'KD-EMP-013', role: 'graphic_designer', designation: 'Media & Production Designer', dept: 'Design & Creative', status: 'active' },
  ];

  return (
    <div className="min-h-screen flex bg-slate-50">
      <Sidebar user={user} />
      <div className="flex-1 flex flex-col min-w-0">
        <Navbar user={user} />
        
        <main className="p-4 sm:p-6 lg:p-8 space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-black text-slate-900">Employees Directory</h1>
              <p className="text-sm text-slate-500 mt-1">14 team members active across 10 departments</p>
            </div>
          </div>

          <div className="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                  <tr>
                    <th className="py-3.5 px-4 sm:px-6">Employee</th>
                    <th className="py-3.5 px-4">Role & Designation</th>
                    <th className="py-3.5 px-4">Department</th>
                    <th className="py-3.5 px-4">Employee Code</th>
                    <th className="py-3.5 px-4 text-center">Status</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {employees.map((emp) => (
                    <tr key={emp.id} className="hover:bg-slate-50/60 transition-colors">
                      <td className="py-3.5 px-4 sm:px-6">
                        <div className="flex items-center gap-3">
                          <div className="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm border border-indigo-200">
                            {emp.name.charAt(0)}
                          </div>
                          <div>
                            <div className="font-bold text-slate-800">{emp.name}</div>
                            <div className="text-xs text-slate-500 flex items-center gap-1">
                              <Mail className="w-3 h-3 text-slate-400" />
                              <span>{emp.email}</span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td className="py-3.5 px-4">
                        <div className="font-medium text-slate-800">{emp.designation}</div>
                        <div className="text-xs text-slate-500 capitalize">{emp.role.replace('_', ' ')}</div>
                      </td>
                      <td className="py-3.5 px-4">
                        <span className="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                          {emp.dept}
                        </span>
                      </td>
                      <td className="py-3.5 px-4 font-mono text-xs text-slate-600">
                        {emp.code}
                      </td>
                      <td className="py-3.5 px-4 text-center">
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <span className="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
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
