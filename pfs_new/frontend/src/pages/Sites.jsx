import { useEffect, useState } from 'react';
import { useSitesStore } from '../lib/stores/sitesStore';
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
import EmptyState from '../components/EmptyState';
import { FaPlus, FaHospital, FaEdit, FaTrash, FaMapMarkerAlt } from 'react-icons/fa';
import { t } from '../lib/translations/index';

export default function Sites() {
  const { locale } = useUIStore();
  const { sites, loading, fetchSites, createSite, updateSite, deleteSite } = useSitesStore();
  const [open, setOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [siteToDelete, setSiteToDelete] = useState(null);
  const [editingSite, setEditingSite] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    code: '',
    province: ''
  });

  useEffect(() => {
    fetchSites();
  }, [fetchSites]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingSite) {
        await updateSite(editingSite.id, formData);
      } else {
        await createSite(formData);
      }
      setOpen(false);
      setEditingSite(null);
      setFormData({ name: '', code: '', province: '' });
      fetchSites();
    } catch (error) {
      console.error('Error saving site:', error);
      alert(error.message || 'Failed to save site');
    }
  };

  const handleEdit = (site) => {
    setEditingSite(site);
    setFormData({ 
      name: site.name || '', 
      code: site.code || '', 
      province: site.province || '' 
    });
    setOpen(true);
  };

  const handleDeleteClick = (id) => {
    setSiteToDelete(id);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!siteToDelete) return;
    
    try {
      await deleteSite(siteToDelete);
      setDeleteDialogOpen(false);
      setSiteToDelete(null);
      fetchSites();
    } catch (error) {
      console.error('Error deleting site:', error);
      alert(error.message || 'Failed to delete site');
      setDeleteDialogOpen(false);
      setSiteToDelete(null);
    }
  };

  const handleNew = () => {
    setEditingSite(null);
    setFormData({ name: '', code: '', province: '' });
    setOpen(true);
  };

  return (
    <div className="space-y-4 sm:space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight">{t(locale, 'admin.sites.title')}</h1>
          <p className="text-sm sm:text-base text-muted-foreground mt-1 sm:mt-2">{t(locale, 'admin.sites.description')}</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <DialogTrigger asChild>
            <Button onClick={handleNew} className="gap-2 w-full sm:w-auto min-h-[44px] touch-manipulation">
              <FaPlus className="h-4 w-4" />
              {t(locale, 'admin.sites.addSite')}
            </Button>
          </DialogTrigger>
          <DialogContent className="w-[95vw] sm:w-full max-w-2xl mx-2 sm:mx-0">
            <DialogHeader>
              <DialogTitle className="text-lg sm:text-xl">
                {editingSite
                  ? t(locale, 'admin.sites.editSite')
                  : t(locale, 'admin.sites.addSite')}
              </DialogTitle>
              <DialogDescription className="text-sm">
                {t(locale, 'admin.sites.formDescription')}
              </DialogDescription>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label className="text-sm sm:text-base">{t(locale, 'admin.sites.siteName')}</Label>
                <Input
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  placeholder="e.g., Health Center 1"
                  required
                  className="min-h-[44px] touch-manipulation text-base sm:text-sm"
                />
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div className="space-y-2">
                  <Label className="text-sm sm:text-base">{t(locale, 'admin.sites.code')}</Label>
                  <Input
                    value={formData.code}
                    onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                    placeholder="e.g., HC001"
                    className="min-h-[44px] touch-manipulation text-base sm:text-sm"
                  />
                </div>
                <div className="space-y-2">
                  <Label className="text-sm sm:text-base">{t(locale, 'admin.sites.province')}</Label>
                  <Input
                    value={formData.province}
                    onChange={(e) => setFormData({ ...formData, province: e.target.value })}
                    placeholder="e.g., Phnom Penh"
                    className="min-h-[44px] touch-manipulation text-base sm:text-sm"
                  />
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
                  {editingSite
                    ? t(locale, 'admin.common.update')
                    : t(locale, 'admin.common.create')}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <FaHospital className="h-5 w-5" />
            {t(locale, 'admin.sites.siteList')}
          </CardTitle>
          <CardDescription>
            {t(locale, 'admin.sites.allSites')}
          </CardDescription>
        </CardHeader>
        <CardContent>
          {loading ? (
            <div className="space-y-3">
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
            </div>
          ) : sites.length === 0 ? (
            <EmptyState
              title={t(locale, 'admin.sites.noSites')}
              description={t(locale, 'admin.sites.noSitesDescription')}
            />
          ) : (
            <div className="rounded-md border">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Site Name</TableHead>
                    <TableHead>Code</TableHead>
                    <TableHead>Location</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead className="text-right">Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {sites.map((site) => (
                    <TableRow key={site.id} className="hover:bg-muted/50">
                      <TableCell className="font-medium">{site.id}</TableCell>
                      <TableCell>
                        <div className="flex items-center gap-2">
                          <FaHospital className="h-4 w-4 text-primary" />
                          <span className="font-medium">{site.name || '-'}</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant="outline">{site.code || 'N/A'}</Badge>
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center gap-1 text-sm text-muted-foreground">
                          <FaMapMarkerAlt className="h-3 w-3" />
                          <span>{site.province || 'Unknown'}</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge variant="secondary">Active</Badge>
                      </TableCell>
                      <TableCell>
                        <div className="flex items-center justify-end gap-2">
                          <Button 
                            variant="outline" 
                            size="sm" 
                            className="gap-1"
                            onClick={() => handleEdit(site)}
                          >
                            <FaEdit className="h-3 w-3" />
                            {t(locale, 'admin.users.edit')}
                          </Button>
                          <Button 
                            variant="destructive" 
                            size="sm" 
                            className="gap-1"
                            onClick={() => handleDeleteClick(site.id)}
                          >
                            <FaTrash className="h-3 w-3" />
                            {t(locale, 'admin.common.delete')}
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          )}
        </CardContent>
      </Card>

      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent className="w-[95vw] sm:w-full mx-2 sm:mx-0">
          <AlertDialogHeader>
            <AlertDialogTitle className="text-lg sm:text-xl">{t(locale, 'admin.common.confirm')}</AlertDialogTitle>
            <AlertDialogDescription className="text-sm">
              {t(locale, 'admin.sites.areYouSureDelete')}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter className="flex-col sm:flex-row gap-2 sm:gap-0">
            <AlertDialogCancel 
              onClick={() => setDeleteDialogOpen(false)}
              className="w-full sm:w-auto min-h-[44px] touch-manipulation order-2 sm:order-1"
            >
              {t(locale, 'admin.common.cancel')}
            </AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDeleteConfirm}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90 w-full sm:w-auto min-h-[44px] touch-manipulation order-1 sm:order-2"
            >
              {t(locale, 'admin.common.delete')}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}
