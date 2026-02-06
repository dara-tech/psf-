import express from 'express';
import { authenticate } from '../middleware/auth.js';
import {
  getDashboard,
  getReportingTable,
  getHFSDashboard,
  getHFSTable,
  getAdminDashboard
} from '../controllers/reportingController.js';

const router = express.Router();

router.use(authenticate);

router.get('/dashboard', getDashboard);
router.post('/dashboard', getDashboard);
router.get('/table', getReportingTable);
router.post('/table', getReportingTable);
router.get('/hfs/dashboard', getHFSDashboard);
router.post('/hfs/dashboard', getHFSDashboard);
router.get('/hfs/table', getHFSTable);
router.post('/hfs/table', getHFSTable);
router.get('/admin/dashboard', getAdminDashboard);
router.post('/admin/dashboard', getAdminDashboard);

export default router;

