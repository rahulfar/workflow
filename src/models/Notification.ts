import mongoose, { Schema, Document } from 'mongoose';

export interface INotification extends Document {
  sql_id?: number;
  user_id: number;
  title: string;
  message: string;
  type: 'info' | 'success' | 'warning' | 'alert';
  is_read: boolean;
  link?: string;
  created_at?: Date;
}

const NotificationSchema = new Schema<INotification>(
  {
    sql_id: { type: Number, index: true },
    user_id: { type: Number, required: true, index: true },
    title: { type: String, required: true },
    message: { type: String, required: true },
    type: {
      type: String,
      enum: ['info', 'success', 'warning', 'alert'],
      default: 'info',
    },
    is_read: { type: Boolean, default: false },
    link: { type: String },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: false } }
);

export default mongoose.models.Notification || mongoose.model<INotification>('Notification', NotificationSchema);
