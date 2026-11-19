import mongoose, { Schema, Document, Model } from 'mongoose';

export interface IProject extends Document {
  sql_id?: number;
  name: string;
  description?: string;
  client_id?: number;
  client_name?: string;
  status: 'active' | 'in_progress' | 'completed' | 'on_hold';
  deadline?: string;
  created_at: Date;
  updated_at: Date;
}

const ProjectSchema = new Schema<IProject>(
  {
    sql_id: { type: Number, index: true },
    name: { type: String, required: true },
    description: { type: String },
    client_id: { type: Number },
    client_name: { type: String },
    status: {
      type: String,
      enum: ['active', 'in_progress', 'completed', 'on_hold'],
      default: 'active',
    },
    deadline: { type: String },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export const Project: Model<IProject> =
  mongoose.models.Project || mongoose.model<IProject>('Project', ProjectSchema);
export default Project;
