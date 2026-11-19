import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IAttendance extends Document {
  user_id: number;
  user_name?: string;
  date: string; // YYYY-MM-DD
  shift_start?: string;
  shift_end?: string;
  clock_in?: string;
  clock_out?: string;
  late_minutes?: number;
  early_exit_minutes?: number;
  total_seconds?: number;
  status: 'present' | 'absent' | 'half_day' | 'late' | 'running';
  notes?: string;
  clock_in_ip?: string;
  clock_out_ip?: string;
  device_type?: string;
  created_at: Date;
  updated_at: Date;
}

const AttendanceSchema = new Schema<IAttendance>(
  {
    user_id: { type: Number, required: true, index: true },
    user_name: { type: String },
    date: { type: String, required: true, index: true },
    shift_start: { type: String, default: '09:40:00' },
    shift_end: { type: String, default: '18:30:00' },
    clock_in: { type: String },
    clock_out: { type: String },
    late_minutes: { type: Number, default: 0 },
    early_exit_minutes: { type: Number, default: 0 },
    total_seconds: { type: Number, default: 0 },
    status: {
      type: String,
      enum: ['present', 'absent', 'half_day', 'late', 'running'],
      default: 'running',
    },
    notes: { type: String },
    clock_in_ip: { type: String },
    clock_out_ip: { type: String },
    device_type: { type: String, default: 'Desktop' },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

AttendanceSchema.index({ user_id: 1, date: 1 }, { unique: true });

export const Attendance: Model<IAttendance> =
  mongoose.models.Attendance || mongoose.model<IAttendance>('Attendance', AttendanceSchema);
export default Attendance;
