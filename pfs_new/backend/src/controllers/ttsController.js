import { textToSpeech } from '../services/ttsService.js';

/**
 * Generate speech from text
 * POST /api/tts/speak
 * Body: { text: string, languageCode?: string, voiceName?: string }
 */
export const speak = async (req, res) => {
  try {
    const { text, languageCode = 'en-US', voiceName } = req.body;

    console.log('🎤 TTS Request:', { text: text?.substring(0, 50), languageCode, voiceName });

    if (!text || text.trim().length === 0) {
      return res.status(400).json({ error: 'Text is required' });
    }

    // Generate audio
    const audioBuffer = await textToSpeech(text, languageCode, voiceName);

    if (!audioBuffer || audioBuffer.length === 0) {
      return res.status(500).json({ 
        error: 'Failed to generate speech',
        details: 'Empty audio buffer received'
      });
    }

    console.log('✅ Audio generated, size:', audioBuffer.length, 'bytes');

    // Set response headers for audio (MP3)
    res.setHeader('Content-Type', 'audio/mpeg');
    res.setHeader('Content-Length', audioBuffer.length);
    res.setHeader('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour

    // Send audio buffer
    res.send(audioBuffer);
  } catch (error) {
    console.error('❌ TTS Controller Error:', error);
    console.error('❌ Error message:', error.message);
    console.error('❌ Error stack:', error.stack);
    res.status(500).json({ 
      error: 'Failed to generate speech',
      details: error.message,
      stack: process.env.NODE_ENV === 'development' ? error.stack : undefined
    });
  }
};
