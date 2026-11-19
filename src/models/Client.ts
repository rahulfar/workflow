import mongoose, { Schema, Document } from 'mongoose';

export interface IClient extends Document {
  sql_id?: number;
  name: string;
  email?: string;
  phone?: string;
  company?: string;
  address?: string;
  status: 'active' | 'inactive';
  created_at?: Date;
  updated_at?: Date;
}

const ClientSchema = new Schema<IClient>(
  {
    sql_id: { type: Number, index: true },
    name: { type: String, required: true },
    email: { type: String, lowercase: true },
    phone: { type: String },
    company: { type: String },
    address: { type: String },
    status: { type: String, enum: ['active', 'inactive'], default: 'active' },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export default mongoose.models.Client || mongoose.model<IClient>('Client', ClientSchema);
