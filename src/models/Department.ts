import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IDepartment extends Document {
  sql_id?: number;
  name: string;
  code: string;
  description?: string;
}

const DepartmentSchema = new Schema<IDepartment>({
  sql_id: { type: Number, index: true },
  name: { type: String, required: true },
  code: { type: String, required: true },
  description: { type: String },
});

export const Department: Model<IDepartment> =
  mongoose.models.Department || mongoose.model<IDepartment>('Department', DepartmentSchema);
export default Department;
