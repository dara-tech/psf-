import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';
import { sequelize } from '../config/database.js';

export const login = async (req, res) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({ error: 'Email and password are required' });
    }

    // Query user from database - explicitly select password column
    console.log(`[Login] Attempting login for email: ${email}`);
    const [users] = await sequelize.query(
      'SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1',
      { replacements: [email] }
    );

    if (users.length === 0) {
      console.log(`[Login] User not found for email: ${email}`);
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const user = users[0];
    console.log(`[Login] User found: ${user.email} (ID: ${user.id})`);

    // Check if user has a password
    if (!user.password) {
      console.error(`[Login] User found but has no password hash for email: ${email}`);
      return res.status(401).json({ error: 'Invalid credentials' });
    }


    // Verify password - Handle both PHP ($2y$) and Node.js ($2b$, $2a$) bcrypt hashes
    try {
      let passwordHash = user.password;
      
      // Convert PHP's $2y$ prefix to $2a$ for bcrypt compatibility
      // $2y$ and $2a$ are algorithmically identical
      if (passwordHash.startsWith('$2y$')) {
        passwordHash = passwordHash.replace(/^\$2y\$/, '$2a$');
      }
      
      console.log(`[Login] Attempting password verification for user: ${email}`);
      console.log(`[Login] Password hash prefix: ${passwordHash.substring(0, 7)}`);
      console.log(`[Login] Password hash length: ${passwordHash.length}`);
      
      const isValidPassword = await bcrypt.compare(password.trim(), passwordHash.trim());
      
      console.log(`[Login] Password comparison result: ${isValidPassword}`);
      
      if (!isValidPassword) {
        console.log(`[Login] Password mismatch for user: ${email}`);
        return res.status(401).json({ error: 'Invalid credentials' });
      }
    } catch (error) {
      console.error('Password comparison error:', error);
      console.error('Error details:', {
        message: error.message,
        stack: error.stack,
        email: email
      });
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    // Get user permissions and roles
    const [permissionsResult] = await sequelize.query(
      `SELECT p.name 
       FROM permissions p
       INNER JOIN role_has_permissions rhp ON p.id = rhp.permission_id
       INNER JOIN model_has_roles mhr ON rhp.role_id = mhr.role_id
       WHERE mhr.model_id = ? AND mhr.model_type = 'App\\\\User'
       UNION
       SELECT p.name
       FROM permissions p
       INNER JOIN model_has_permissions mhp ON p.id = mhp.permission_id
       WHERE mhp.model_id = ? AND mhp.model_type = 'App\\\\User'`,
      { replacements: [user.id, user.id] }
    );

    const [rolesResult] = await sequelize.query(
      `SELECT r.id, r.name
       FROM roles r
       INNER JOIN model_has_roles mhr ON r.id = mhr.role_id
       WHERE mhr.model_id = ? AND mhr.model_type = 'App\\\\User'
       ORDER BY r.id ASC
       LIMIT 1`,
      { replacements: [user.id] }
    );

    const permissions = permissionsResult.map(p => p.name);
    const roles = rolesResult.map(r => r.name);
    const roleId = rolesResult.length > 0 ? rolesResult[0].id : null;

    // Generate JWT token
    const token = jwt.sign(
      { id: user.id, email: user.email, name: user.name, roleId },
      process.env.JWT_SECRET || 'your-secret-key',
      { expiresIn: process.env.JWT_EXPIRES_IN || '24h' }
    );

    // Store token in session
    req.session.token = token;

    res.json({
      token,
      user: {
        id: user.id,
        name: user.name,
        email: user.email
      },
      permissions,
      roles
    });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};

export const logout = async (req, res) => {
  req.session.destroy();
  res.json({ message: 'Logged out successfully' });
};

export const getCurrentUser = async (req, res) => {
  try {
    const [users] = await sequelize.query(
      'SELECT id, name, email FROM users WHERE id = ? LIMIT 1',
      { replacements: [req.user.id] }
    );

    if (users.length === 0) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Get user permissions and roles
    const [permissionsResult] = await sequelize.query(
      `SELECT p.name 
       FROM permissions p
       INNER JOIN role_has_permissions rhp ON p.id = rhp.permission_id
       INNER JOIN model_has_roles mhr ON rhp.role_id = mhr.role_id
       WHERE mhr.model_id = ? AND mhr.model_type = 'App\\\\User'
       UNION
       SELECT p.name
       FROM permissions p
       INNER JOIN model_has_permissions mhp ON p.id = mhp.permission_id
       WHERE mhp.model_id = ? AND mhp.model_type = 'App\\\\User'`,
      { replacements: [req.user.id, req.user.id] }
    );

    const [rolesResult] = await sequelize.query(
      `SELECT r.name
       FROM roles r
       INNER JOIN model_has_roles mhr ON r.id = mhr.role_id
       WHERE mhr.model_id = ? AND mhr.model_type = 'App\\\\User'`,
      { replacements: [req.user.id] }
    );

    const permissions = permissionsResult.map(p => p.name);
    const roles = rolesResult.map(r => r.name);

    res.json({ 
      user: users[0],
      permissions,
      roles
    });
  } catch (error) {
    console.error('Get current user error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};

export const changePassword = async (req, res) => {
  try {
    const { currentPassword, newPassword } = req.body;
    const userId = req.user.id;

    if (!currentPassword || !newPassword) {
      return res.status(400).json({ error: 'Current and new passwords are required' });
    }

    // Get user
    const [users] = await sequelize.query(
      'SELECT password FROM users WHERE id = ? LIMIT 1',
      { replacements: [userId] }
    );

    if (users.length === 0) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Verify current password - handle both PHP ($2y$) and Node.js ($2b$, $2a$) hashes
    let passwordHash = users[0].password;
    if (passwordHash.startsWith('$2y$')) {
      passwordHash = passwordHash.replace(/^\$2y\$/, '$2a$');
    }
    const isValidPassword = await bcrypt.compare(currentPassword, passwordHash);
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Current password is incorrect' });
    }

    // Hash new password
    const hashedPassword = await bcrypt.hash(newPassword, 10);

    // Update password
    await sequelize.query(
      'UPDATE users SET password = ? WHERE id = ?',
      { replacements: [hashedPassword, userId] }
    );

    res.json({ message: 'Password changed successfully' });
  } catch (error) {
    console.error('Change password error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
};

export const forgotPassword = async (req, res) => {
  // TODO: Implement password reset email functionality
  res.json({ message: 'Password reset email sent' });
};

export const resetPassword = async (req, res) => {
  // TODO: Implement password reset functionality
  res.json({ message: 'Password reset successfully' });
};

