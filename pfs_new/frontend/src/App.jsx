import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuthStore } from './lib/store';
import Login from './pages/Login';
import Layout from './components/Layout';
import Patients from './pages/Patients';
import Reporting from './pages/Reporting';
import HFSDashboard from './pages/HFSDashboard';
import HFS from './pages/HFS';
import AdminDashboard from './pages/AdminDashboard';
import Users from './pages/Users';
import Permissions from './pages/Permissions';
import Roles from './pages/Roles';
import Sites from './pages/Sites';
import Devices from './pages/Devices';
import QuestionManager from './pages/QuestionManager';
import ChangePassword from './pages/ChangePassword';
import Settings from './pages/Settings';
import SurveyAnalysis from './pages/SurveyAnalysis';
import ClientQuestionnaire from './pages/ClientQuestionnaire';
import ProviderQuestionnaire from './pages/ProviderQuestionnaire';
import SiteSelection from './pages/SiteSelection';
import QRCodeGenerator from './pages/QRCodeGenerator';
import OfflineIndicator from './components/OfflineIndicator';

function PrivateRoute({ children }) {
  const token = useAuthStore((state) => state.token);
  return token ? children : <Navigate to="/login" />;
}

function App() {
  return (
    <>
      <Routes>
        <Route path="/login" element={<Login />} />
        {/* Public questionnaire routes - no authentication required */}
        {/* Redirect root paths to site selection (like old system) */}
        <Route path="/client" element={<Navigate to="/client/index/kh" replace />} />
        <Route path="/provider" element={<Navigate to="/provider/index/kh" replace />} />
        {/* Site selection pages - must come before token routes */}
        <Route path="/client/index/:locale?" element={<SiteSelection />} />
        <Route path="/provider/index/:locale?" element={<SiteSelection />} />
        <Route path="/client/:token/:locale?" element={<ClientQuestionnaire />} />
        <Route path="/client/:token/:locale/:uuid/:index" element={<ClientQuestionnaire />} />
        <Route path="/provider/:token/:locale?" element={<ProviderQuestionnaire />} />
        <Route path="/provider/:token/:locale/:uuid/:index" element={<ProviderQuestionnaire />} />
        <Route
          path="/*"
          element={
            <PrivateRoute>
              <Layout>
                <Routes>
                  <Route path="/" element={<Navigate to="/patients" replace />} />
                  <Route path="/patients" element={<Patients />} />
                  <Route path="/reporting" element={<Reporting />} />
                  <Route path="/hfs_dashboard" element={<HFSDashboard />} />
                  <Route path="/hfs" element={<HFS />} />
                  <Route path="/admin_dashboard" element={<AdminDashboard />} />
                  <Route path="/users" element={<Users />} />
                  <Route path="/permissions" element={<Permissions />} />
                  <Route path="/roles" element={<Roles />} />
                  <Route path="/sites" element={<Sites />} />
                  <Route path="/devices" element={<Devices />} />
                  <Route path="/questions" element={<QuestionManager />} />
                  <Route path="/survey-analysis" element={<SurveyAnalysis />} />
                  <Route path="/qr-codes" element={<QRCodeGenerator />} />
                  <Route path="/change_password" element={<ChangePassword />} />
                  <Route path="/settings" element={<Settings />} />
                </Routes>
              </Layout>
            </PrivateRoute>
          }
        />
      </Routes>
      <OfflineIndicator />
    </>
  );
}

export default App;

