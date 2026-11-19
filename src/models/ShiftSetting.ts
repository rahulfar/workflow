import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IShiftSetting extends Document {
  start_time: string;
  end_time: string;
  lunch_start: string;
  lunch_end: string;
  grace_period_minutes: number;
  half_day_hours: number;
  full_day_hours: number;
  working_days: string;
  updated_at: Date;
}

const ShiftSettingSchema = new Schema<IShiftSetting>(
  {
    start_time: { type: String, default: '09:40:00' },
    end_time: { type: String, default: '18:30:00' },
    lunch_start: { type: String, default: '13:30:00' },
    lunch_end: { type: String, default: '14:30:00' },
    grace_period_minutes: { type: Number, default: 15 },
    half_day_hours: { type: Number, default: 4.5 },
    full_day_hours: { type: Number, default: 8.83 },
    working_days: {
      type: String,
      default: 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
    },
  },
  { timestamps: { createdAt: false, updatedAt: 'updated_at' } }
);

export const ShiftSetting: Model<IShiftSetting> =
  mongoose.models.ShiftSetting || mongoose.model<IShiftSetting>('ShiftSetting', ShiftSettingSchema);
export default ShiftSetting;
