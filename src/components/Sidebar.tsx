'use client';

import React from 'react';
import Link from 'next/navigation';
import { usePathname } from 'next/navigation';
import { 
  LayoutDashboard, 
  Users, 
  CheckSquare, 
  Calendar, 
  Smartphone, 
  ShieldAlert, 
  Cpu, 
  Building2,
  FolderGit2
} from 'lucide-react';

interface SidebarProps {
  user: {
    id: number | string;
    name: string;
    email: string;
    role: string;
  };
}

export default function Sidebar({ user }: SidebarProps) {
  const pathname = usePathname();

  const navItems = [
    {
      label: 'Dashboard',
      href: '/dashboard',
      icon: LayoutDashboard,
      roles: ['admin', 'manager', 'product_manager', 'employee'],
    },
    {
      label: 'Employees Directory',
      href: '/employees',
      icon: Users,
      roles: ['admin', 'manager', 'product_manager'],
    },
    {
      label: 'Tasks & Projects',
      href: '/tasks',
      icon: CheckSquare,
      roles: ['admin', 'manager', 'product_manager', 'employee'],
    },
    {
      label: 'Attendance & Logs',
      href: '/attendance',
      icon: Calendar,
      roles: ['admin', 'manager', 'product_manager', 'employee'],
    },
    {
      label: 'Wi-Fi Shift Clock-In',
      href: '/mobile-shift',
      icon: Smartphone,
      roles: ['admin', 'manager', 'product_manager', 'employee'],
    },
    {
      label: 'Office IP Whitelist',
      href: '/ip-settings',
      icon: ShieldAlert,
      roles: ['admin', 'manager'],
    },
    {
      label: 'MCP AI Protocol',
      href: '/mcp',
      icon: Cpu,
      roles: ['admin', 'manager', 'product_manager', 'employee'],
    },
  ];

  const allowedItems = navItems.filter(item => item.roles.includes(user.role));

  return (
    <aside className="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between hidden md:flex min-h-screen border-r border-slate-800">
      <div>
        {/* Brand header */}
        <div className="h-16 flex items-center gap-3 px-6 border-b border-slate-800">
          <div className="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-black text-white text-lg shadow-md shadow-indigo-500/20">
            W
          </div>
          <div>
            <div className="font-bold text-white text-base leading-none">WorkFlow <span className="text-indigo-400">Pro</span></div>
            <div className="text-[10px] text-slate-400 uppercase tracking-widest mt-1">Enterprise</div>
          </div>
        </div>

        {/* Navigation links */}
        <div className="p-4 space-y-1">
          <div className="px-3 py-2 text-[11px] font-bold uppercase tracking-wider text-slate-500">
            Navigation
          </div>
          {allowedItems.map((item) => {
            const Icon = item.icon;
            const isActive = pathname === item.href || (item.href === '/dashboard' && pathname.includes('/dashboard'));

            return (
              <a
                key={item.href}
                href={item.href}
                className={`flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all ${
                  isActive
                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30'
                    : 'text-slate-400 hover:text-white hover:bg-slate-800/60'
                }`}
              >
                <Icon className="w-4 h-4 flex-shrink-0" />
                <span>{item.label}</span>
              </a>
            );
          })}
        </div>
      </div>

      {/* Footer Info */}
      <div className="p-4 border-t border-slate-800/80 m-2 rounded-2xl bg-slate-800/40 text-xs text-slate-400">
        <div className="font-semibold text-slate-300 mb-1">Wi-Fi Shift Guard</div>
        <div className="text-[11px] text-slate-500">Office IP: 192.168.1.22 / Subnets</div>
        <div className="mt-2 text-[10px] text-indigo-400 font-mono">Next.js 15 & MongoDB</div>
      </div>
    </aside>
  );
}
