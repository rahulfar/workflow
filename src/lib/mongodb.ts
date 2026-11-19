import mongoose from 'mongoose';

export function getMongoUri(): string {
  // 1. Highest priority: User's connected database from Vercel Integration Store
  if (process.env.MY_WORKFLOW_MONGODB_URI) {
    return process.env.MY_WORKFLOW_MONGODB_URI;
  }
  // 2. Any other integration store connection variable
  for (const [key, val] of Object.entries(process.env)) {
    if (key.endsWith('_MONGODB_URI') && val) {
      return val;
    }
  }
  // 3. Fallback to MONGODB_URI (ignore placeholder if present)
  if (process.env.MONGODB_URI && !process.env.MONGODB_URI.includes('cluster0.workflow.mongodb.net')) {
    return process.env.MONGODB_URI;
  }
  if (process.env.MONGODB_URL) {
    return process.env.MONGODB_URL;
  }
  if (process.env.DATABASE_URL && process.env.DATABASE_URL.startsWith('mongodb')) {
    return process.env.DATABASE_URL;
  }
  return process.env.MONGODB_URI || 'mongodb://localhost:27017/workflow_pro';
}

interface MongooseCache {
  conn: typeof mongoose | null;
  promise: Promise<typeof mongoose> | null;
}

declare global {
  var mongooseCache: MongooseCache | undefined;
}

let cached: MongooseCache = global.mongooseCache || { conn: null, promise: null };

if (!global.mongooseCache) {
  global.mongooseCache = cached;
}

export async function connectToDatabase(): Promise<typeof mongoose> {
  if (cached.conn) {
    return cached.conn;
  }

  const uri = getMongoUri();

  if (!cached.promise) {
    const opts = {
      bufferCommands: false,
      maxPoolSize: 10,
      serverSelectionTimeoutMS: 3000,
      connectTimeoutMS: 3000,
    };

    cached.promise = mongoose.connect(uri, opts).then((mongooseInstance) => {
      console.log(`Connected to MongoDB successfully: ${uri.replace(/:([^:@]{3,})@/, ':****@')}`);
      return mongooseInstance;
    });
  }

  try {
    cached.conn = await cached.promise;
  } catch (e) {
    cached.promise = null;
    throw e;
  }

  return cached.conn;
}

export default connectToDatabase;
