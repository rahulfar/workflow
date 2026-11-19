<?php
/**
 * MCP Direct Gateway for WorkFlow Pro
 * Provides direct URL resolution for https://kraftworflow.in/mcp/
 */
$_GET['route'] = '/mcp';
require_once dirname(__DIR__) . '/api/mcp.php';
