import mongoose, { Schema, Document } from 'mongoose';

export interface IActivityLog extends Document {
  sql_id?: number;
  user_id?: number;
  user_name?: string;
  action: string;
  module: string;
  details?: string;
  ip_address?: string;
  created_at?: Date;
}

const ActivityLogSchema = new Schema<IActivityLog>(
  {
    sql_id: { type: Number, index: true },
    user_id: { type: Number, index: true },
    user_name: { type: String },
    action: { type: String, required: true },
    module: { type: String, required: true },
    details: { type: String },
    ip_address: { type: String },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: false } }
);

export default mongoose.models.ActivityLog || mongoose.model<IActivityLog>('ActivityLog', ActivityLogSchema);
