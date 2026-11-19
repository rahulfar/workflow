import mongoose, { Schema, Document } from 'mongoose';

export interface ILeaveRequest extends Document {
  sql_id?: number;
  user_id: number;
  leave_type: 'casual' | 'sick' | 'earned' | 'emergency';
  start_date: string;
  end_date: string;
  total_days: number;
  reason: string;
  status: 'pending' | 'approved' | 'rejected';
  approved_by?: number;
  created_at?: Date;
  updated_at?: Date;
}

const LeaveRequestSchema = new Schema<ILeaveRequest>(
  {
    sql_id: { type: Number, index: true },
    user_id: { type: Number, required: true, index: true },
    leave_type: {
      type: String,
      enum: ['casual', 'sick', 'earned', 'emergency'],
      default: 'casual',
    },
    start_date: { type: String, required: true },
    end_date: { type: String, required: true },
    total_days: { type: Number, default: 1 },
    reason: { type: String, required: true },
    status: {
      type: String,
      enum: ['pending', 'approved', 'rejected'],
      default: 'pending',
    },
    approved_by: { type: Number },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export default mongoose.models.LeaveRequest || mongoose.model<ILeaveRequest>('LeaveRequest', LeaveRequestSchema);
