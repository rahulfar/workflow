import { redirect } from 'next/navigation';
import { getSession } from '@/lib/auth';

export default async function DashboardRedirect() {
  const user = await getSession();

  if (!user) {
    redirect('/login');
  }

  if (user.role === 'admin') redirect('/admin/dashboard');
  if (user.role === 'manager') redirect('/manager/dashboard');
  if (user.role === 'product_manager') redirect('/product-manager/dashboard');
  redirect('/employee/dashboard');
}
