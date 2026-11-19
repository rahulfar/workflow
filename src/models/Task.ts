import mongoose, { Schema, Document, Model } from 'mongoose';

export interface ITask extends Document {
  sql_id?: number;
  title: string;
  description?: string;
  status: 'pending' | 'in_progress' | 'completed' | 'flagged' | 'pending_approval' | 'cancelled';
  priority: 'low' | 'medium' | 'high' | 'urgent';
  due_date?: string;
  project_id?: number;
  project_name?: string;
  assigned_to: number;
  assignee_name?: string;
  created_by?: number;
  created_at: Date;
  updated_at: Date;
}

const TaskSchema = new Schema<ITask>(
  {
    sql_id: { type: Number, index: true },
    title: { type: String, required: true },
    description: { type: String },
    status: {
      type: String,
      enum: ['pending', 'in_progress', 'completed', 'flagged', 'pending_approval', 'cancelled'],
      default: 'pending',
      index: true,
    },
    priority: {
      type: String,
      enum: ['low', 'medium', 'high', 'urgent'],
      default: 'medium',
    },
    due_date: { type: String },
    project_id: { type: Number },
    project_name: { type: String },
    assigned_to: { type: Number, required: true, index: true },
    assignee_name: { type: String },
    created_by: { type: Number },
  },
  { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } }
);

export const Task: Model<ITask> = mongoose.models.Task || mongoose.model<ITask>('Task', TaskSchema);
export default Task;
