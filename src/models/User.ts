import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IUser extends Document {
  sql_id?: number;
  name: string;
  email: string;
  username?: string;
  password?: string;
  role_id: number;
  role_category: 'admin' | 'manager' | 'product_manager' | 'employee';
  role_title?: string;
  department_id: number;
  department_name?: string;
  employee_code?: string;
  designation?: string;
  shift_timing?: string;
  avatar?: string;
  phone?: string;
  status: 'active' | 'inactive';
  created_at: Date;
  updated_at: Date;
}

const UserSchema = new Schema<IUser>(
  {
    sql_id: { type: Number, index: true },
    name: { type: String, required: true },
    email: { type: String, required: true, unique: true, lowercase: true, trim: true },
    username: { type: String, lowercase: true, trim: true },
    password: { type: String },
    role_id: { type: Number, default: 4 },
    role_category: {
      type: String,
      enum: ['admin', 'manager', 'product_manager', 'employee'],
      default: 'employee',
      index: true,
    },
    role_title: { type: String, default: 'Employee' },
    department_id: { type: Number, default: 1 },
    department_name: { type: String, default: 'Development' },
    employee_code: { type: String },
    designation: { type: String },
    shift_timing: { type: String, default: '09:40 AM - 06:30 PM (Fixed Office)' },
    avatar: { type: String },
    phone: { type: String },
    status: { type: String, enum: ['active', 'inactive'], default: 'active' },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export const User: Model<IUser> = mongoose.models.User || mongoose.model<IUser>('User', UserSchema);
export default User;
