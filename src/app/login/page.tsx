'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import { ShieldCheck, UserCheck, Briefcase, Users, Lock, Mail, ArrowRight, AlertCircle, CheckCircle } from 'lucide-react';

const ROLES = [
  {
    id: 'admin',
    name: 'Admin',
    description: 'Executive workspace & team control',
    icon: ShieldCheck,
    color: 'from-blue-600 to-indigo-700',
    border: 'border-blue-500',
    defaultEmail: 'kartikeyan@kraftingdigital.com',
  },
  {
    id: 'manager',
    name: 'Manager',
    description: 'Operations, attendance & departments',
    icon: UserCheck,
    color: 'from-emerald-600 to-teal-700',
    border: 'border-emerald-500',
    defaultEmail: 'mrinali@kraftingdigital.com',
  },
  {
    id: 'product_manager',
    name: 'Product Manager',
    description: 'Roadmaps, sprints & task tracking',
    icon: Briefcase,
    color: 'from-amber-600 to-orange-700',
    border: 'border-amber-500',
    defaultEmail: 'contact@kraftingdigital.com',
  },
  {
    id: 'employee',
    name: 'Employee',
    description: 'Daily tasks, timeline & Wi-Fi shift clock-in',
    icon: Users,
    color: 'from-purple-600 to-violet-700',
    border: 'border-purple-500',
    defaultEmail: 'finance@kraftingdigital.com',
  },
];

export default function LoginPage() {
  const router = useRouter();
  const [selectedRole, setSelectedRole] = useState('admin');
  const [email, setEmail] = useState('kartikeyan@kraftingdigital.com');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);

  const handleRoleSelect = (roleId: string, defaultEmail: string) => {
    setSelectedRole(roleId);
    setEmail(defaultEmail);
    setError(null);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);

    if (!email) {
      setError('Please enter your username or email address.');
      return;
    }
    if (!password) {
      setError('Please enter your password.');
      return;
    }

    setLoading(true);

    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          role: selectedRole,
          email,
          password,
        }),
      });

      const data = await res.json();

      if (!res.ok || !data.success) {
        throw new Error(data.message || 'Authentication failed. Please verify your credentials.');
      }

      setSuccess(`Welcome back, ${data.data.name || 'User'}! Redirecting...`);
      setTimeout(() => {
        router.push(data.data.redirect || '/dashboard');
      }, 700);
    } catch (err: any) {
      setError(err.message || 'Connection error. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-center p-4 sm:p-6 lg:p-8">
      {/* Background Glow */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/15 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/15 rounded-full blur-3xl"></div>
      </div>

      <div className="w-full max-w-5xl bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10">
        
        {/* Left Hero / Brand Side */}
        <div className="lg:col-span-5 p-8 lg:p-12 bg-gradient-to-br from-indigo-900/60 to-slate-950/80 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-white/10">
          <div>
            <div className="flex items-center gap-3 mb-8">
              <div className="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white font-black text-2xl">
                W
              </div>
              <div>
                <h1 className="text-2xl font-black tracking-tight text-white">WorkFlow <span className="text-indigo-400">Pro</span></h1>
                <p className="text-xs text-indigo-200/80 font-medium">Enterprise Management Suite</p>
              </div>
            </div>

            <div className="space-y-4 my-8">
              <h2 className="text-3xl font-extrabold text-white leading-tight">
                Next-Gen Workspace Management
              </h2>
              <p className="text-sm text-indigo-200/80 leading-relaxed">
                Seamless role orchestration, team analytics, task tracking, and office Wi-Fi shift verification.
              </p>
            </div>

            <div className="space-y-3 pt-4 border-t border-white/10 text-xs text-indigo-200/70">
              <div className="flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Real-Time Office Wi-Fi Geofencing</span>
              </div>
              <div className="flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-indigo-400"></span>
                <span>Powered by Next.js & MongoDB</span>
              </div>
              <div className="flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>Full-Stack Role Security & Verification</span>
              </div>
            </div>
          </div>

          <div className="pt-8 text-xs text-indigo-300/60 font-mono">
            v2.5.0 Next.js Production Build
          </div>
        </div>

        {/* Right Form Side */}
        <div className="lg:col-span-7 p-6 sm:p-8 lg:p-12 bg-white flex flex-col justify-center">
          <div className="mb-6">
            <h2 className="text-2xl font-black text-slate-900">Sign In to Your Workspace</h2>
            <p className="text-sm text-slate-500 mt-1">Select your role account category to proceed</p>
          </div>

          {/* Role Category Selector Cards */}
          <div className="grid grid-cols-2 gap-3 mb-6">
            {ROLES.map((role) => {
              const Icon = role.icon;
              const isSelected = selectedRole === role.id;
              return (
                <button
                  type="button"
                  key={role.id}
                  onClick={() => handleRoleSelect(role.id, role.defaultEmail)}
                  className={`p-3.5 rounded-2xl text-left border transition-all duration-200 flex flex-col justify-between ${
                    isSelected
                      ? `bg-indigo-50/70 border-indigo-600 ring-2 ring-indigo-600/20 shadow-sm`
                      : 'bg-slate-50/70 border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                  }`}
                >
                  <div className="flex items-center justify-between mb-2">
                    <span className={`p-2 rounded-xl text-white bg-gradient-to-br ${role.color} shadow-sm`}>
                      <Icon className="w-4 h-4" />
                    </span>
                    {isSelected && (
                      <span className="w-2 h-2 rounded-full bg-indigo-600"></span>
                    )}
                  </div>
                  <div>
                    <div className="font-bold text-sm text-slate-800">{role.name}</div>
                    <div className="text-[11px] text-slate-500 line-clamp-1">{role.description}</div>
                  </div>
                </button>
              );
            })}
          </div>

          {/* Alerts */}
          {error && (
            <div className="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-2.5 animate-in fade-in duration-200">
              <AlertCircle className="w-5 h-5 flex-shrink-0 mt-0.5" />
              <span>{error}</span>
            </div>
          )}

          {success && (
            <div className="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-start gap-2.5 animate-in fade-in duration-200">
              <CheckCircle className="w-5 h-5 flex-shrink-0 mt-0.5" />
              <span>{success}</span>
            </div>
          )}

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                Username or Email Address
              </label>
              <div className="relative">
                <Mail className="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                <input
                  type="text"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="name@kraftingdigital.com"
                  className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                  required
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                Password
              </label>
              <div className="relative">
                <Lock className="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                <input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••••••"
                  className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                  required
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed group mt-2"
            >
              {loading ? (
                <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
              ) : (
                <>
                  <span>Sign In as {ROLES.find(r => r.id === selectedRole)?.name}</span>
                  <ArrowRight className="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
                </>
              )}
            </button>
          </form>

          <div className="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>Office Wi-Fi Geofence: Active</span>
            <span>Secure TLS 1.3</span>
          </div>
        </div>

      </div>
    </div>
  );
}
