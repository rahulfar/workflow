import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IAuthorizedIp extends Document {
  ip_address: string;
  location: string;
  status: 'active' | 'inactive';
  notes?: string;
  created_at: Date;
  updated_at: Date;
}

const AuthorizedIpSchema = new Schema<IAuthorizedIp>(
  {
    ip_address: { type: String, required: true, trim: true, index: true },
    location: { type: String, required: true, trim: true },
    status: { type: String, enum: ['active', 'inactive'], default: 'active' },
    notes: { type: String },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export const AuthorizedIp: Model<IAuthorizedIp> =
  mongoose.models.AuthorizedIp || mongoose.model<IAuthorizedIp>('AuthorizedIp', AuthorizedIpSchema);
export default AuthorizedIp;
