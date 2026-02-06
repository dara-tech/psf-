import { useEffect, useState } from 'react';
import { usePermissionsStore } from '../lib/stores/permissionsStore';
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
import { FaPlus, FaShieldAlt, FaEdit, FaTrash } from 'react-icons/fa';
import { t } from '../lib/translations/index';

export default function Permissions() {
  const { locale } = useUIStore();
  const { permissions, loading, fetchPermissions, createPermission, updatePermission, deletePermission } = usePermissionsStore();
  const [open, setOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [permissionToDelete, setPermissionToDelete] = useState(null);
  const [editingPermission, setEditingPermission] = useState(null);
  const [formData, setFormData] = useState({
    name: ''
  });

  useEffect(() => {
    fetchPermissions();
  }, [fetchPermissions]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingPermission) {
        await updatePermission(editingPermission.id, formData);
      } else {
        await createPermission(formData);
      }
      setOpen(false);
      setEditingPermission(null);
      setFormData({ name: '' });
      fetchPermissions();
    } catch (error) {
      console.error('Error saving permission:', error);
      alert(error.message || 'Failed to save permission');
    }
  };

  const handleEdit = (permission) => {
    setEditingPermission(permission);
    setFormData({ name: permission.name });
    setOpen(true);
  };

  const handleDeleteClick = (id) => {
    setPermissionToDelete(id);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!permissionToDelete) return;
    
    try {
      await deletePermission(permissionToDelete);
      setDeleteDialogOpen(false);
      setPermissionToDelete(null);
      fetchPermissions();
    } catch (error) {
      console.error('Error deleting permission:', error);
      alert(error.message || 'Failed to delete permission');
      setDeleteDialogOpen(false);
      setPermissionToDelete(null);
    }
  };

  const handleNew = () => {
    setEditingPermission(null);
    setFormData({ name: '' });
    setOpen(true);
  };

  return (
    <div className="space-y-4 sm:space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight">{t(locale, 'admin.permissions.title')}</h1>
          <p className="text-sm sm:text-base text-muted-foreground mt-1 sm:mt-2">{t(locale, 'admin.permissions.description')}</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <DialogTrigger asChild>
            <Button onClick={handleNew} className="gap-2 w-full sm:w-auto min-h-[44px] touch-manipulation">
              <FaPlus className="h-4 w-4" />
              {t(locale, 'admin.permissions.addPermission')}
            </Button>
          </DialogTrigger>
          <DialogContent className="w-[95vw] sm:w-full max-w-md mx-2 sm:mx-0">
            <DialogHeader>
              <DialogTitle className="text-lg sm:text-xl">
                {editingPermission
                  ? t(locale, 'admin.permissions.editPermission')
                  : t(locale, 'admin.permissions.addPermission')}
              </DialogTitle>
              <DialogDescription className="text-sm">
                {t(locale, 'admin.permissions.formDescription')}
              </DialogDescription>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label className="text-sm sm:text-base">{t(locale, 'admin.permissions.permissionName')}</Label>
                <Input
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  placeholder="e.g., users_manage"
                  required
                  className="min-h-[44px] touch-manipulation text-base sm:text-sm"
                />
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
                  {editingPermission
                    ? t(locale, 'admin.common.update')
                    : t(locale, 'admin.common.create')}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <Card>
        <CardHeader className="p-4 sm:p-6">
          <CardTitle className="flex items-center gap-2 text-lg sm:text-xl">
            <FaShieldAlt className="h-4 w-4 sm:h-5 sm:w-5" />
            {t(locale, 'admin.permissions.permissionList')}
          </CardTitle>
          <CardDescription className="text-sm">
            {t(locale, 'admin.permissions.allPermissions')}
          </CardDescription>
        </CardHeader>
        <CardContent className="p-4 sm:p-6">
          {loading ? (
            <div className="space-y-3">
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
            </div>
          ) : permissions.length === 0 ? (
            <EmptyState
              title={t(locale, 'admin.permissions.noPermissions')}
              description={t(locale, 'admin.permissions.noPermissionsDescription')}
            />
          ) : (
            <div className="rounded-md border overflow-x-auto -mx-4 sm:mx-0">
              <div className="min-w-full inline-block">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead className="min-w-[60px]">ID</TableHead>
                      <TableHead className="min-w-[200px]">Permission Name</TableHead>
                      <TableHead className="min-w-[100px]">Status</TableHead>
                      <TableHead className="text-right min-w-[180px]">Actions</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {permissions.map((permission) => (
                      <TableRow key={permission.id} className="hover:bg-muted/50">
                        <TableCell className="font-medium">{permission.id}</TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <FaShieldAlt className="h-4 w-4 text-primary flex-shrink-0" />
                            <span className="font-medium truncate">{permission.name}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <Badge variant="secondary">Active</Badge>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center justify-end gap-2 flex-wrap">
                            <Button 
                              variant="outline" 
                              size="sm" 
                              className="gap-1 min-h-[36px] touch-manipulation text-xs sm:text-sm"
                              onClick={() => handleEdit(permission)}
                            >
                              <FaEdit className="h-3 w-3" />
                              <span className="hidden sm:inline">{t(locale, 'admin.users.edit')}</span>
                              <span className="sm:hidden">Edit</span>
                            </Button>
                            <Button 
                              variant="destructive" 
                              size="sm" 
                              className="gap-1 min-h-[36px] touch-manipulation text-xs sm:text-sm"
                              onClick={() => handleDeleteClick(permission.id)}
                            >
                              <FaTrash className="h-3 w-3" />
                              <span className="hidden sm:inline">{t(locale, 'admin.common.delete')}</span>
                              <span className="sm:hidden">Del</span>
                            </Button>
                          </div>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            </div>
          )}
        </CardContent>
      </Card>

      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent className="w-[95vw] sm:w-full mx-2 sm:mx-0">
          <AlertDialogHeader>
            <AlertDialogTitle className="text-lg sm:text-xl">{t(locale, 'admin.common.confirm')}</AlertDialogTitle>
            <AlertDialogDescription className="text-sm">
              {t(locale, 'admin.permissions.areYouSureDelete')}
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

