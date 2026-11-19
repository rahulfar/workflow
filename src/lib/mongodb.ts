import mongoose from 'mongoose';

const DEFAULT_ATLAS_URI =
  'mongodb+srv://Vercel-Admin-workflow:nNQZyouAUZ2rSdRb@workflow.gqj8ogh.mongodb.net/workflow_pro?retryWrites=true&w=majority';

export function getMongoUri(): string {
  let rawUri = '';

  // 1. Highest priority: User's connected database from Vercel Integration Store
  if (process.env.MY_WORKFLOW_MONGODB_URI) {
    rawUri = process.env.MY_WORKFLOW_MONGODB_URI;
  } else {
    for (const [key, val] of Object.entries(process.env)) {
      if (key.endsWith('_MONGODB_URI') && val) {
        rawUri = val;
        break;
      }
    }
  }

  // 2. Fallback to standard environment variables
  if (!rawUri && process.env.MONGODB_URI && !process.env.MONGODB_URI.includes('cluster0.workflow.mongodb.net')) {
    rawUri = process.env.MONGODB_URI;
  } else if (!rawUri && process.env.MONGODB_URL) {
    rawUri = process.env.MONGODB_URL;
  } else if (!rawUri && process.env.DATABASE_URL && process.env.DATABASE_URL.startsWith('mongodb')) {
    rawUri = process.env.DATABASE_URL;
  }

  if (!rawUri) {
    rawUri = DEFAULT_ATLAS_URI;
  }

  // Automatically replace placeholder password if present in environment variable
  if (rawUri.includes('<db_password>')) {
    rawUri = rawUri.replace('<db_password>', 'nNQZyouAUZ2rSdRb');
  }

  return rawUri;
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
