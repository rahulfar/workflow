import { headers } from 'next/headers';
import connectToDatabase from './mongodb';
import AuthorizedIp from '@/models/AuthorizedIp';

export async function getClientIp(): Promise<string> {
  const headerList = await headers();

  const forwarded = headerList.get('x-forwarded-for');
  if (forwarded) {
    const ips = forwarded.split(',').map((s) => s.trim());
    if (ips.length > 0 && ips[0]) {
      return ips[0];
    }
  }

  const realIp = headerList.get('x-real-ip');
  if (realIp) return realIp.trim();

  const vercelIp = headerList.get('x-vercel-forwarded-for');
  if (vercelIp) return vercelIp.split(',')[0].trim();

  return '127.0.0.1';
}

export function isIpMatching(clientIp: string, rule: string): boolean {
  const cIp = clientIp.trim();
  const pattern = rule.trim();

  if (cIp === pattern) return true;

  // Wildcard match (e.g. 192.168.1.* or 2401:4900:*)
  if (pattern.includes('*')) {
    const prefix = pattern.replace(/\*$/, '');
    if (cIp.startsWith(prefix)) return true;
  }

  return false;
}

export async function checkClientIpAuthorized(clientIp?: string): Promise<{
  authorized: boolean;
  ip: string;
  location?: string;
}> {
  await connectToDatabase();
  const detectedIp = clientIp || (await getClientIp());

  // Loopback check for local development
  if (detectedIp === '127.0.0.1' || detectedIp === '::1' || detectedIp === 'localhost') {
    return { authorized: true, ip: detectedIp, location: 'Local Loopback' };
  }

  const activeRules = await AuthorizedIp.find({ status: 'active' }).lean();

  for (const rule of activeRules) {
    if (isIpMatching(detectedIp, rule.ip_address)) {
      return { authorized: true, ip: detectedIp, location: rule.location };
    }
  }

  return { authorized: false, ip: detectedIp };
}
