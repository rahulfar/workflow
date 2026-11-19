import mongoose, { Schema, Document } from 'mongoose';

export interface IHoliday extends Document {
  sql_id?: number;
  name: string;
  holiday_date: string; // YYYY-MM-DD
  day_of_week?: string;
  is_optional: boolean;
  created_at?: Date;
}

const HolidaySchema = new Schema<IHoliday>(
  {
    sql_id: { type: Number, index: true },
    name: { type: String, required: true },
    holiday_date: { type: String, required: true, index: true },
    day_of_week: { type: String },
    is_optional: { type: Boolean, default: false },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: false } }
);

export default mongoose.models.Holiday || mongoose.model<IHoliday>('Holiday', HolidaySchema);
