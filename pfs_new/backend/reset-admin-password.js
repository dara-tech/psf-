import bcrypt from 'bcrypt';
import { sequelize } from './src/config/database.js';
import dotenv from 'dotenv';

dotenv.config();

const newPassword = 'password';
const adminEmail = 'admin@admin.com';

async function resetPassword() {
  try {
    // Connect to database
    await sequelize.authenticate();
    console.log('✅ Database connected');

    // Hash new password
    const hashedPassword = await bcrypt.hash(newPassword, 10);
    console.log('✅ Password hashed:', hashedPassword.substring(0, 20) + '...');

    // Update admin password
    const [result] = await sequelize.query(
      'UPDATE users SET password = ? WHERE email = ?',
      { replacements: [hashedPassword, adminEmail] }
    );

    console.log('✅ Admin password reset successfully!');
    console.log(`   Email: ${adminEmail}`);
    console.log(`   New Password: ${newPassword}`);
    console.log('\nYou can now login with these credentials.');

    // Verify the new password works
    const [users] = await sequelize.query(
      'SELECT password FROM users WHERE email = ? LIMIT 1',
      { replacements: [adminEmail] }
    );

    if (users.length > 0) {
      const isValid = await bcrypt.compare(newPassword, users[0].password);
      console.log(`\n✅ Password verification test: ${isValid ? 'PASSED' : 'FAILED'}`);
    }

    process.exit(0);
  } catch (error) {
    console.error('❌ Error:', error);
    process.exit(1);
  }
}

resetPassword();

