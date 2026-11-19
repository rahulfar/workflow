/**
 * MongoDB Data Importer & Seeder - WorkFlow Pro
 * Reads database/mongodb_seed_data.json and seeds all 51 collections into MongoDB
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

    const explicitCollectionMapping = {
      'roles': 'roles',
      'departments': 'departments',
      'users': 'users',
      'authorized_ips': 'authorizedips',
      'shift_settings': 'shiftsettings',
      'tasks': 'tasks',
      'projects': 'projects',
      'attendance': 'attendances',
      'chat_messages': 'chatmessages',
      'meetings': 'meetings',
      'leave_requests': 'leaverequests',
      'activity_logs': 'activitylogs',
      'notifications': 'notifications',
      'settings': 'settings',
      'payrolls': 'payrolls',
      'clients': 'clients',
      'services': 'services',
      'holidays': 'holidays',
      'emergency_task_requests': 'emergencytaskrequests',
      'task_flags': 'taskflags',
      'employees': 'employees',
      'employee_profiles': 'employeeprofiles',
      'employee_salaries': 'employeesalaries',
      'ai_action_plans': 'aiactionplans'
    };

    let totalCollections = 0;
    let totalDocuments = 0;

    for (const [tableKey, records] of Object.entries(data)) {
      if (!records || records.length === 0) continue;

      const collectionName = explicitCollectionMapping[tableKey] || tableKey.replace(/_/g, '').toLowerCase();
      const coll = db.collection(collectionName);

      let docs = records.map(r => {
        const doc = { ...r };
        if (doc.id !== undefined) {
          doc.sql_id = doc.id;
        }
        // Normalize role_category for users
        if (tableKey === 'users') {
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

      // Deduplicate emails for users table
      if (tableKey === 'users') {
        const seenEmails = new Set();
        docs = docs.map((doc) => {
          if (doc.email) {
            const lower = doc.email.toLowerCase();
            if (seenEmails.has(lower)) {
              doc.email = `emp_${doc.id || Math.floor(Math.random() * 1000)}_${lower}`;
            } else {
              seenEmails.add(lower);
            }
          }
          return doc;
        });
      }

      await coll.deleteMany({});
      await coll.insertMany(docs);
      console.log(`  ✓ Seeded ${docs.length} records into '${collectionName}' (from ${tableKey})`);
      totalCollections++;
      totalDocuments += docs.length;
    }

    // Create Indexes
    try {
      await db.collection('users').createIndex({ email: 1 }, { unique: true });
      await db.collection('authorizedips').createIndex({ ip_address: 1 });
      await db.collection('attendances').createIndex({ user_id: 1, date: 1 });
    } catch (idxErr) {
      console.warn('Index note:', idxErr.message);
    }

    console.log('\n=============================================');
    console.log(`SUCCESS: Seeded ${totalDocuments} documents across ${totalCollections} collections!`);
    console.log('=============================================');
  } catch (err) {
    console.error('Migration error:', err);
  } finally {
    await client.close();
  }
}

runMigration();
