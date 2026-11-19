import { SignJWT, jwtVerify } from 'jose';
import { cookies } from 'next/headers';
import bcrypt from 'bcryptjs';

const JWT_SECRET = new TextEncoder().encode(
  process.env.JWT_SECRET || 'workflow-super-secret-production-jwt-key-32chars!!'
);

export interface SessionUser {
  id: string;
  sql_id?: number;
  name: string;
  email: string;
  role: 'admin' | 'manager' | 'product_manager' | 'employee';
  role_title?: string;
  department_name?: string;
  avatar?: string;
}

export function verifyPassword(password: string, storedHash: string): boolean {
  if (!password || !storedHash) return false;
  // Normalize PHP $2y$ blowfish hash to $2a$ for bcryptjs
  const normalizedHash = storedHash.replace(/^\$2y\$/, '$2a$');
  try {
    return bcrypt.compareSync(password, normalizedHash);
  } catch (err) {
    return false;
  }
}

export function hashPassword(password: string): string {
  return bcrypt.hashSync(password, 12);
}

export async function createSession(user: SessionUser): Promise<string> {
  const token = await new SignJWT({ ...user })
    .setProtectedHeader({ alg: 'HS256' })
    .setIssuedAt()
    .setExpirationTime('7d')
    .sign(JWT_SECRET);

  const cookieStore = await cookies();
  cookieStore.set('workflow_token', token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 7 * 24 * 60 * 60,
    path: '/',
  });

  return token;
}

export async function getSession(): Promise<SessionUser | null> {
  const cookieStore = await cookies();
  const token = cookieStore.get('workflow_token')?.value;

  if (!token) return null;

  try {
    const { payload } = await jwtVerify(token, JWT_SECRET);
    return payload as unknown as SessionUser;
  } catch (err) {
    return null;
  }
}

export async function getSessionUser(token?: string): Promise<SessionUser | null> {
  if (token) {
    try {
      const { payload } = await jwtVerify(token, JWT_SECRET);
      return payload as unknown as SessionUser;
    } catch (err) {
      return null;
    }
  }
  return getSession();
}

export async function destroySession(): Promise<void> {
  const cookieStore = await cookies();
  cookieStore.delete('workflow_token');
}
