import { useEffect, useState } from 'react';
import { useUIStore } from '../lib/stores/uiStore';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table';
import { Button } from '../components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '../components/ui/dialog';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '../components/ui/alert-dialog';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Skeleton } from '../components/ui/skeleton';
import { Badge } from '../components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { Textarea } from '../components/ui/textarea';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '../components/ui/tabs';
import LoadingSpinner from '../components/LoadingSpinner';
import EmptyState from '../components/EmptyState';
import { FaPlus, FaTrash, FaEdit, FaQuestionCircle, FaVolumeUp } from 'react-icons/fa';
import { t } from '../lib/translations/index';
import api from '../lib/api';
import AudioRecorder from '../components/AudioRecorder';

export default function QuestionManager() {
  const { locale } = useUIStore();
  const [questions, setQuestions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('all');
  const [open, setOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [questionToDelete, setQuestionToDelete] = useState(null);
  const [editingQuestion, setEditingQuestion] = useState(null);
  const [formData, setFormData] = useState({
    questionKey: '',
    questionnaireType: 'client', // 'client' or 'provider'
    section: '',
    questionType: 'text', // 'text', 'radio', 'checkbox', 'textarea'
    textEn: '',
    textKh: '',
    audioUrlEn: null,
    audioUrlKh: null,
    order: 0,
    isActive: true
  });
  const [uploadingEn, setUploadingEn] = useState(false);
  const [uploadingKh, setUploadingKh] = useState(false);

  useEffect(() => {
    fetchQuestions();
  }, []);

  const fetchQuestions = async () => {
    try {
      setLoading(true);
      const response = await api.get('/admin/questions');
      setQuestions(response.data.questions || []);
    } catch (error) {
      console.error('Error fetching questions:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const dataToSend = {
        ...formData,
        audioUrlEn: formData.audioUrlEn || null,
        audioUrlKh: formData.audioUrlKh || null
      };
      
      if (editingQuestion) {
        await api.put(`/admin/questions/${editingQuestion.id}`, dataToSend);
      } else {
        await api.post('/admin/questions', dataToSend);
      }
      setOpen(false);
      setEditingQuestion(null);
      setFormData({
        questionKey: '',
        questionnaireType: 'client',
        section: '',
        questionType: 'text',
        textEn: '',
        textKh: '',
        audioUrlEn: null,
        audioUrlKh: null,
        order: 0,
        isActive: true
      });
      fetchQuestions();
    } catch (error) {
      console.error('Error saving question:', error);
      alert(error.response?.data?.error || 'Failed to save question');
    }
  };

  const handleEdit = (question) => {
    setEditingQuestion(question);
    setFormData({
      questionKey: question.question_key,
      questionnaireType: question.questionnaire_type,
      section: question.section,
      questionType: question.question_type,
      textEn: question.text_en,
      textKh: question.text_kh,
      audioUrlEn: question.audio_url_en || null,
      audioUrlKh: question.audio_url_kh || null,
      order: question.order,
      isActive: question.is_active
    });
    setOpen(true);
  };

  const handleDeleteClick = (id) => {
    setQuestionToDelete(id);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!questionToDelete) return;
    
    try {
      await api.delete(`/admin/questions/${questionToDelete}`);
      setDeleteDialogOpen(false);
      setQuestionToDelete(null);
      fetchQuestions();
    } catch (error) {
      console.error('Error deleting question:', error);
      alert(error.response?.data?.error || 'Failed to delete question');
      setDeleteDialogOpen(false);
      setQuestionToDelete(null);
    }
  };

  const handleNew = () => {
    setEditingQuestion(null);
    const defaultType = activeTab === 'all' ? 'client' : activeTab;
    setFormData({
      questionKey: '',
      questionnaireType: defaultType,
      section: '',
      questionType: 'text',
      textEn: '',
      textKh: '',
      audioUrlEn: null,
      audioUrlKh: null,
      order: questions.length + 1,
      isActive: true
    });
    setOpen(true);
  };

  const handleAudioUpload = async (file, language) => {
    if (!file) return;
    
    if (!formData.questionKey) {
      alert('Please enter a question key first');
      return;
    }

    const formDataToSend = new FormData();
    formDataToSend.append('audio', file);
    formDataToSend.append('questionKey', formData.questionKey);
    formDataToSend.append('language', language);

    try {
      if (language === 'en') {
        setUploadingEn(true);
      } else {
        setUploadingKh(true);
      }

      const response = await api.post('/admin/questions/upload-audio', formDataToSend, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      if (language === 'en') {
        setFormData({ ...formData, audioUrlEn: response.data.url });
        setUploadingEn(false);
      } else {
        setFormData({ ...formData, audioUrlKh: response.data.url });
        setUploadingKh(false);
      }

      alert('Audio uploaded successfully!');
    } catch (error) {
      console.error('Error uploading audio:', error);
      alert(error.response?.data?.error || 'Failed to upload audio');
      if (language === 'en') {
        setUploadingEn(false);
      } else {
        setUploadingKh(false);
      }
    }
  };

  // Filter questions based on active tab
  const filteredQuestions = activeTab === 'all' 
    ? questions 
    : questions.filter(q => q.questionnaire_type === activeTab);

  if (loading) {
    return (
      <div className="space-y-4">
        <Skeleton className="h-10 w-64" />
        <Skeleton className="h-96 w-full" />
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold">{t(locale, 'admin.questions.title')}</h1>
          <p className="text-muted-foreground mt-1">
            {t(locale, 'admin.questions.description')}
          </p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <DialogTrigger asChild>
            <Button onClick={handleNew}>
              <FaPlus className="mr-2" />
              {t(locale, 'admin.questions.addQuestion')}
            </Button>
          </DialogTrigger>
          <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
            <DialogHeader>
              <DialogTitle>
                {editingQuestion
                  ? t(locale, 'admin.questions.editQuestion')
                  : t(locale, 'admin.questions.addQuestion')}
              </DialogTitle>
              <DialogDescription>
                {t(locale, 'admin.questions.formDescription')}
              </DialogDescription>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div className="space-y-2">
                  <Label>{t(locale, 'admin.questions.questionKey')}</Label>
                  <Input
                    value={formData.questionKey}
                    onChange={(e) => setFormData({ ...formData, questionKey: e.target.value })}
                    placeholder="e.g., q1a"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label>{t(locale, 'admin.questions.questionnaireType')}</Label>
                  <Select
                    value={formData.questionnaireType}
                    onValueChange={(value) => setFormData({ ...formData, questionnaireType: value })}
                  >
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="client">{t(locale, 'admin.questions.client')}</SelectItem>
                      <SelectItem value="provider">{t(locale, 'admin.questions.provider')}</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div className="space-y-2">
                  <Label>{t(locale, 'admin.questions.section')}</Label>
                  <Input
                    value={formData.section}
                    onChange={(e) => setFormData({ ...formData, section: e.target.value })}
                    placeholder="e.g., section_1A"
                    required
                    className="min-h-[44px] touch-manipulation"
                  />
                </div>
                <div className="space-y-2">
                  <Label>{t(locale, 'admin.questions.questionType')}</Label>
                  <Select
                    value={formData.questionType}
                    onValueChange={(value) => setFormData({ ...formData, questionType: value })}
                  >
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="text">{t(locale, 'admin.questions.text')}</SelectItem>
                      <SelectItem value="radio">{t(locale, 'admin.questions.radio')}</SelectItem>
                      <SelectItem value="checkbox">{t(locale, 'admin.questions.checkbox')}</SelectItem>
                      <SelectItem value="textarea">{t(locale, 'admin.questions.textarea')}</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="space-y-2">
                <Label>{t(locale, 'admin.questions.textEn')}</Label>
                <Textarea
                  value={formData.textEn}
                  onChange={(e) => setFormData({ ...formData, textEn: e.target.value })}
                  placeholder="Question text in English"
                  rows={3}
                  required
                />
              </div>

              <div className="space-y-2">
                <Label>{t(locale, 'admin.questions.textKh')}</Label>
                <Textarea
                  value={formData.textKh}
                  onChange={(e) => setFormData({ ...formData, textKh: e.target.value })}
                  placeholder="Question text in Khmer"
                  rows={3}
                  required
                />
              </div>

              <div className="space-y-4 p-3 sm:p-5 rounded-lg border-2 border-primary/20 bg-gradient-to-br from-muted/50 to-muted/30 shadow-sm">
                <div className="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-4 pb-3 border-b">
                  <div className="p-2 rounded-lg bg-primary/10 flex-shrink-0">
                    <FaVolumeUp className="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                  </div>
                  <div className="flex-1 min-w-0">
                    <Label className="text-sm sm:text-base font-semibold block">Voice-over Audio</Label>
                    <p className="text-xs sm:text-sm text-muted-foreground mt-0.5">
                      Record or upload audio files for questions
                    </p>
                  </div>
                </div>
                
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                  <div className="space-y-3 sm:space-y-4 p-3 sm:p-4 rounded-lg border bg-card shadow-sm hover:shadow-md transition-shadow">
                    <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 pb-2 border-b">
                      <div className="flex items-center gap-2">
                        <div className="h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                        <Label className="text-sm font-semibold">English Audio</Label>
                      </div>
                      {formData.audioUrlEn && (
                        <Badge variant="default" className="flex items-center gap-1.5 bg-green-600 hover:bg-green-700 shadow-sm text-xs sm:text-sm w-fit">
                          <FaCheckCircle className="h-3 w-3" />
                          <span>Uploaded</span>
                        </Badge>
                      )}
                    </div>
                    <AudioRecorder
                      onUpload={async (file) => {
                        await handleAudioUpload(file, 'en');
                      }}
                      disabled={!formData.questionKey}
                      language="en"
                    />
                    <div className="pt-3 border-t space-y-2">
                      <Label className="text-xs font-medium text-muted-foreground block">Or upload existing file:</Label>
                      <div className="relative">
                        <Input
                          type="file"
                          accept="audio/*"
                          onChange={(e) => {
                            const file = e.target.files?.[0];
                            if (file) {
                              handleAudioUpload(file, 'en');
                            }
                          }}
                          disabled={uploadingEn || !formData.questionKey}
                          className="text-xs sm:text-sm cursor-pointer file:mr-2 sm:file:mr-4 file:py-2 sm:file:py-1.5 file:px-2 sm:file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 w-full min-h-[44px] touch-manipulation"
                        />
                      </div>
                    </div>
                  </div>

                  <div className="space-y-3 sm:space-y-4 p-3 sm:p-4 rounded-lg border bg-card shadow-sm hover:shadow-md transition-shadow">
                    <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 pb-2 border-b">
                      <div className="flex items-center gap-2">
                        <div className="h-2 w-2 rounded-full bg-purple-500 flex-shrink-0"></div>
                        <Label className="text-sm font-semibold">Khmer Audio</Label>
                      </div>
                      {formData.audioUrlKh && (
                        <Badge variant="default" className="flex items-center gap-1.5 bg-green-600 hover:bg-green-700 shadow-sm text-xs sm:text-sm w-fit">
                          <FaCheckCircle className="h-3 w-3" />
                          <span>Uploaded</span>
                        </Badge>
                      )}
                    </div>
                    <AudioRecorder
                      onUpload={async (file) => {
                        await handleAudioUpload(file, 'kh');
                      }}
                      disabled={!formData.questionKey}
                      language="kh"
                    />
                    <div className="pt-3 border-t space-y-2">
                      <Label className="text-xs font-medium text-muted-foreground block">Or upload existing file:</Label>
                      <div className="relative">
                        <Input
                          type="file"
                          accept="audio/*"
                          onChange={(e) => {
                            const file = e.target.files?.[0];
                            if (file) {
                              handleAudioUpload(file, 'kh');
                            }
                          }}
                          disabled={uploadingKh || !formData.questionKey}
                          className="text-xs sm:text-sm cursor-pointer file:mr-2 sm:file:mr-4 file:py-2 sm:file:py-1.5 file:px-2 sm:file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 w-full min-h-[44px] touch-manipulation"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                {!formData.questionKey && (
                  <div className="p-3 rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 flex items-start gap-2">
                    <span className="text-amber-600 dark:text-amber-400 text-sm flex-shrink-0">⚠️</span>
                    <p className="text-xs sm:text-sm text-amber-800 dark:text-amber-200 leading-relaxed">
                      Please enter a <strong>Question Key</strong> first to enable audio recording and upload functionality.
                    </p>
                  </div>
                )}
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div className="space-y-2">
                  <Label>{t(locale, 'admin.questions.order')}</Label>
                  <Input
                    type="number"
                    value={formData.order}
                    onChange={(e) => setFormData({ ...formData, order: parseInt(e.target.value) || 0 })}
                    min="0"
                  />
                </div>
                <div className="space-y-2">
                  <Label>{t(locale, 'admin.questions.isActive')}</Label>
                  <Select
                    value={formData.isActive ? 'true' : 'false'}
                    onValueChange={(value) => setFormData({ ...formData, isActive: value === 'true' })}
                  >
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="true">{t(locale, 'admin.common.yes')}</SelectItem>
                      <SelectItem value="false">{t(locale, 'admin.common.no')}</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <DialogFooter className="flex-col sm:flex-row gap-2 sm:gap-0 pt-2">
                <Button 
                  type="button" 
                  variant="outline" 
                  onClick={() => setOpen(false)}
                  className="w-full sm:w-auto min-h-[44px] touch-manipulation order-2 sm:order-1"
                >
                  {t(locale, 'admin.common.cancel')}
                </Button>
                <Button 
                  type="submit"
                  className="w-full sm:w-auto min-h-[44px] touch-manipulation order-1 sm:order-2"
                >
                  {editingQuestion
                    ? t(locale, 'admin.common.update')
                    : t(locale, 'admin.common.create')}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
        <TabsList className="grid w-full max-w-md grid-cols-3">
          <TabsTrigger value="all" className="whitespace-nowrap">{t(locale, 'admin.common.all')}</TabsTrigger>
          <TabsTrigger value="client" className="whitespace-nowrap">{t(locale, 'admin.questions.client')}</TabsTrigger>
          <TabsTrigger value="provider" className="whitespace-nowrap">{t(locale, 'admin.questions.provider')}</TabsTrigger>
        </TabsList>

        <TabsContent value={activeTab} className="mt-6">
          {filteredQuestions.length === 0 ? (
            <EmptyState
              icon={FaQuestionCircle}
              title={t(locale, 'admin.questions.noQuestions')}
              description={t(locale, 'admin.questions.noQuestionsDescription')}
            />
          ) : (
            <Card>
              <CardHeader>
                <CardTitle>{t(locale, 'admin.questions.allQuestions')}</CardTitle>
                <CardDescription>
                  {t(locale, 'admin.questions.totalQuestions', { count: filteredQuestions.length })}
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="rounded-md border">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>{t(locale, 'admin.questions.questionKey')}</TableHead>
                        <TableHead>{t(locale, 'admin.questions.type')}</TableHead>
                        <TableHead>{t(locale, 'admin.questions.section')}</TableHead>
                        <TableHead>{locale === 'en' ? t(locale, 'admin.questions.textEn') : t(locale, 'admin.questions.textKh')}</TableHead>
                        <TableHead>{t(locale, 'admin.questions.order')}</TableHead>
                        <TableHead>{t(locale, 'admin.questions.status')}</TableHead>
                        <TableHead className="text-right">{t(locale, 'admin.common.actions')}</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {filteredQuestions
                        .sort((a, b) => a.order - b.order)
                        .map((question) => (
                      <TableRow key={question.id}>
                        <TableCell className="font-mono text-sm">{question.question_key}</TableCell>
                        <TableCell>
                          <Badge variant="outline">
                            {question.questionnaire_type === 'client'
                              ? t(locale, 'admin.questions.client')
                              : t(locale, 'admin.questions.provider')}
                          </Badge>
                        </TableCell>
                        <TableCell>{question.section}</TableCell>
                        <TableCell className="max-w-xs truncate">{locale === 'en' ? question.text_en : question.text_kh}</TableCell>
                        <TableCell>{question.order}</TableCell>
                        <TableCell>
                          <Badge variant={question.is_active ? 'default' : 'secondary'}>
                            {question.is_active
                              ? t(locale, 'admin.common.active')
                              : t(locale, 'admin.common.inactive')}
                          </Badge>
                        </TableCell>
                        <TableCell className="text-right">
                          <div className="flex justify-end gap-2">
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleEdit(question)}
                            >
                              <FaEdit />
                            </Button>
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleDeleteClick(question.id)}
                            >
                              <FaTrash className="text-destructive" />
                            </Button>
                          </div>
                        </TableCell>
                      </TableRow>
                        ))}
                    </TableBody>
                  </Table>
                </div>
              </CardContent>
            </Card>
          )}
        </TabsContent>
      </Tabs>

      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>{t(locale, 'admin.common.confirm')}</AlertDialogTitle>
            <AlertDialogDescription>
              {t(locale, 'admin.questions.areYouSureDelete')}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel onClick={() => setDeleteDialogOpen(false)}>
              {t(locale, 'admin.common.cancel')}
            </AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDeleteConfirm}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
            >
              {t(locale, 'admin.common.delete')}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}

