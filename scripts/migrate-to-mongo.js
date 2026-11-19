/**
 * MongoDB Data Importer & Seeder
 * Reads database/mongodb_seed_data.json and seeds MongoDB collections
 */
const { MongoClient } = require('mongodb');
const fs = require('fs');
const path = require('path');

const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://localhost:27017/workflow_pro';
const SEED_FILE = path.join(__dirname, '..', 'database', 'mongodb_seed_data.json');

async function runMigration() {
  console.log(`Connecting to MongoDB at: ${MONGODB_URI.replace(/:([^:@]{3,})@/, ':****@')}...`);
  const client = new MongoClient(MONGODB_URI);

  try {
    await client.connect();
    console.log('Connected to MongoDB successfully!');
    const db = client.db();

    if (!fs.existsSync(SEED_FILE)) {
      console.error(`Seed file not found at: ${SEED_FILE}`);
      process.exit(1);
    }

    const raw = fs.readFileSync(SEED_FILE, 'utf8');
    const data = JSON.parse(raw);

    const mapping = [
      { key: 'roles', collection: 'roles' },
      { key: 'departments', collection: 'departments' },
      { key: 'users', collection: 'users' },
      { key: 'authorized_ips', collection: 'authorizedips' },
      { key: 'shift_settings', collection: 'shiftsettings' },
      { key: 'tasks', collection: 'tasks' },
      { key: 'projects', collection: 'projects' },
      { key: 'attendance', collection: 'attendances' },
      { key: 'chat_messages', collection: 'chatmessages' },
      { key: 'meetings', collection: 'meetings' },
      { key: 'leave_requests', collection: 'leaverequests' },
      { key: 'activity_logs', collection: 'activitylogs' },
      { key: 'notifications', collection: 'notifications' },
      { key: 'settings', collection: 'settings' }
    ];

    for (const m of mapping) {
      const records = data[m.key] || [];
      if (records.length === 0) continue;

      const coll = db.collection(m.collection);
      console.log(`Migrating ${records.length} records into '${m.collection}'...`);

      // Transform record sql_id
      const docs = records.map(r => {
        const doc = { ...r };
        if (doc.id !== undefined) {
          doc.sql_id = doc.id;
        }
        // Normalize role_category for users
        if (m.key === 'users') {
          if (!doc.role_category) {
            const roleId = Number(doc.role_id);
            if (roleId === 1) doc.role_category = 'admin';
            else if (roleId === 2) doc.role_category = 'manager';
            else if (roleId === 3) doc.role_category = 'product_manager';
            else doc.role_category = 'employee';
          }
        }
        return doc;
      });

      // Clear existing and insert
      await coll.deleteMany({});
      await coll.insertMany(docs);
      console.log(`  ✓ Inserted ${docs.length} into '${m.collection}'`);
    }

    console.log('\n=============================================');
    console.log('MONGODB SEEDING COMPLETED SUCCESSFULLY!');
    console.log('=============================================');
  } catch (err) {
    console.error('Migration error:', err);
  } finally {
    await client.close();
  }
}

runMigration();
