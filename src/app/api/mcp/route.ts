import { NextRequest, NextResponse } from 'next/server';

export async function GET(req: NextRequest) {
  return NextResponse.json({
    jsonrpc: '2.0',
    result: {
      serverInfo: {
        name: 'WorkFlow Pro MCP Server',
        version: '2.5.0',
      },
      capabilities: {
        tools: { listChanged: false },
        resources: { subscribe: false, listChanged: false },
        prompts: { listChanged: false },
      },
    },
  });
}

export async function POST(req: NextRequest) {
  try {
    const body = await req.json();
    const { id, method, params } = body;

    if (method === 'initialize') {
      return NextResponse.json({
        jsonrpc: '2.0',
        id,
        result: {
          protocolVersion: '2024-11-05',
          serverInfo: {
            name: 'WorkFlow Pro MCP Server',
            version: '2.5.0',
          },
          capabilities: {
            tools: {},
            resources: {},
            prompts: {},
          },
        },
      });
    }

    if (method === 'tools/list') {
      return NextResponse.json({
        jsonrpc: '2.0',
        id,
        result: {
          tools: [
            {
              name: 'get_team_status',
              description: 'Retrieve real-time live attendance, on-shift staff count, and working status',
              inputSchema: { type: 'object', properties: {} },
            },
            {
              name: 'verify_shift_status',
              description: 'Check Wi-Fi geofence authorization and active shift for an employee',
              inputSchema: {
                type: 'object',
                properties: { employee_id: { type: 'number' } },
                required: ['employee_id'],
              },
            },
          ],
        },
      });
    }

    return NextResponse.json({
      jsonrpc: '2.0',
      id,
      result: {
        status: 'ok',
        timestamp: new Date().toISOString(),
      },
    });
  } catch (err: any) {
    return NextResponse.json({
      jsonrpc: '2.0',
      error: { code: -32603, message: err.message || 'Internal error' },
    }, { status: 500 });
  }
}
