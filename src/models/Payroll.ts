import mongoose, { Schema, Document } from 'mongoose';

export interface IPayroll extends Document {
  sql_id?: number;
  user_id: number;
  employee_name?: string;
  month_year: string; // e.g. "2026-11"
  basic_salary: number;
  allowances: number;
  deductions: number;
  net_salary: number;
  payment_status: 'paid' | 'pending' | 'processing';
  payment_date?: Date;
  created_at?: Date;
  updated_at?: Date;
}

const PayrollSchema = new Schema<IPayroll>(
  {
    sql_id: { type: Number, index: true },
    user_id: { type: Number, required: true, index: true },
    employee_name: { type: String },
    month_year: { type: String, required: true, index: true },
    basic_salary: { type: Number, default: 0 },
    allowances: { type: Number, default: 0 },
    deductions: { type: Number, default: 0 },
    net_salary: { type: Number, default: 0 },
    payment_status: {
      type: String,
      enum: ['paid', 'pending', 'processing'],
      default: 'pending',
    },
    payment_date: { type: Date },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export default mongoose.models.Payroll || mongoose.model<IPayroll>('Payroll', PayrollSchema);
