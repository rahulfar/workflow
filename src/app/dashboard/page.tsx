import { cookies } from 'next/headers';
import { redirect } from 'next/navigation';
import { getSessionUser } from '@/lib/auth';

export default async function DashboardRedirect() {
  const cookieStore = await cookies();
  const token = cookieStore.get('auth_token')?.value;
  const user = token ? await getSessionUser(token) : null;

  if (!user) {
    redirect('/login');
  }

  if (user.role === 'admin') redirect('/admin/dashboard');
  if (user.role === 'manager') redirect('/manager/dashboard');
  if (user.role === 'product_manager') redirect('/product-manager/dashboard');
  redirect('/employee/dashboard');
}
