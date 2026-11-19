import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IRole extends Document {
  sql_id?: number;
  name: string;
  display_name: string;
  category: 'admin' | 'manager' | 'product_manager' | 'employee';
  description?: string;
}

const RoleSchema = new Schema<IRole>({
  sql_id: { type: Number, index: true },
  name: { type: String, required: true, unique: true },
  display_name: { type: String, required: true },
  category: {
    type: String,
    enum: ['admin', 'manager', 'product_manager', 'employee'],
    default: 'employee',
  },
  description: { type: String },
});

export const Role: Model<IRole> = mongoose.models.Role || mongoose.model<IRole>('Role', RoleSchema);
export default Role;
