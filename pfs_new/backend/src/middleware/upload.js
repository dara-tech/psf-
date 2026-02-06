import multer from 'multer';

// Configure multer for memory storage (we'll upload directly to Cloudinary)
const storage = multer.memoryStorage();

// File filter for audio files only
const fileFilter = (req, file, cb) => {
  // Accept audio files
  if (file.mimetype.startsWith('audio/') || 
      file.mimetype === 'audio/mpeg' || 
      file.mimetype === 'audio/mp3' ||
      file.mimetype === 'audio/wav' ||
      file.mimetype === 'audio/ogg' ||
      file.mimetype === 'audio/webm') {
    cb(null, true);
  } else {
    cb(new Error('Only audio files are allowed'), false);
  }
};

// Configure multer
export const uploadAudio = multer({
  storage,
  fileFilter,
  limits: {
    fileSize: 10 * 1024 * 1024 // 10MB limit
  }
});
