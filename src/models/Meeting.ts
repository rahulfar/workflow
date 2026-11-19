import mongoose, { Schema, Document } from 'mongoose';

export interface IMeeting extends Document {
  sql_id?: number;
  title: string;
  description?: string;
  organizer_id: number;
  start_time: Date;
  end_time?: Date;
  meeting_url?: string;
  status: 'scheduled' | 'active' | 'completed' | 'cancelled';
  created_at?: Date;
}

const MeetingSchema = new Schema<IMeeting>(
  {
    sql_id: { type: Number, index: true },
    title: { type: String, required: true },
    description: { type: String },
    organizer_id: { type: Number, required: true, index: true },
    start_time: { type: Date, required: true },
    end_time: { type: Date },
    meeting_url: { type: String },
    status: {
      type: String,
      enum: ['scheduled', 'active', 'completed', 'cancelled'],
      default: 'scheduled',
    },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export default mongoose.models.Meeting || mongoose.model<IMeeting>('Meeting', MeetingSchema);
