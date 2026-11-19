import mongoose from 'mongoose';
import connectToDatabase from './mongodb';
import User from '@/models/User';
import seedData from '../../database/mongodb_seed_data.json';

export function getStaticSeedData() {
  return seedData;
}

export async function ensureUsersSeeded() {
  try {
    await connectToDatabase();
    const count = await User.countDocuments();
    if (count > 0) return;

    console.log('MongoDB users collection is empty. Seeding initial users...');
    const users = (seedData.users || []).map((u: any) => {
      const roleId = Number(u.role_id);
      const roleCategory =
        roleId === 1
          ? 'admin'
          : roleId === 2
          ? 'manager'
          : roleId === 3
          ? 'product_manager'
          : 'employee';

      return {
        sql_id: u.id,
        name: u.name,
        email: u.email?.toLowerCase().trim(),
        username: (u.username || u.name).toLowerCase().trim(),
        password: u.password || u.password_hash,
        role_id: roleId,
        role_category: roleCategory,
        role_title: u.designation || 'Staff',
        department_id: u.department_id || 1,
        department_name: 'Engineering',
        employee_code: u.employee_code,
        designation: u.designation,
        shift_timing: u.shift_timing || '09:40 AM - 06:30 PM (Fixed Office)',
        avatar: u.avatar || 'default_avatar.png',
        phone: u.phone,
        status: u.status || 'active',
      };
    });

    if (users.length > 0) {
      await User.insertMany(users);
      console.log(`Successfully seeded ${users.length} users into MongoDB!`);
    }
  } catch (err) {
    console.error('Error seeding users:', err);
  }
}
