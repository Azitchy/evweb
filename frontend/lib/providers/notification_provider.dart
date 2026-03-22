import 'package:flutter/material.dart';
import '../models/models.dart';
import '../services/api_service.dart';

class NotificationProvider extends ChangeNotifier {
  final ApiService _api = ApiService();
  List<AppNotification> _notifications = [];
  int _unreadCount = 0;
  bool _isLoading = false;

  List<AppNotification> get notifications => _notifications;
  int get unreadCount => _unreadCount;
  bool get isLoading => _isLoading;

  Future<void> fetchNotifications() async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await _api.getNotifications();
      final list = data['data'] as List;
      _notifications = list.map((e) => AppNotification.fromJson(e)).toList();
    } catch (_) {}

    _isLoading = false;
    notifyListeners();
  }

  Future<void> fetchUnreadCount() async {
    try {
      final data = await _api.getUnreadNotificationCount();
      _unreadCount = data['count'] ?? 0;
      notifyListeners();
    } catch (_) {}
  }

  Future<void> markAsRead(int id) async {
    try {
      await _api.markNotificationAsRead(id);
      final index = _notifications.indexWhere((n) => n.id == id);
      if (index != -1) {
        _notifications[index] = AppNotification(
          id: _notifications[index].id,
          title: _notifications[index].title,
          body: _notifications[index].body,
          type: _notifications[index].type,
          isRead: true,
          createdAt: _notifications[index].createdAt,
        );
        _unreadCount = _unreadCount > 0 ? _unreadCount - 1 : 0;
        notifyListeners();
      }
    } catch (_) {}
  }

  Future<void> markAllAsRead() async {
    try {
      await _api.markAllNotificationsAsRead();
      _notifications = _notifications
          .map(
            (n) => AppNotification(
              id: n.id,
              title: n.title,
              body: n.body,
              type: n.type,
              isRead: true,
              createdAt: n.createdAt,
            ),
          )
          .toList();
      _unreadCount = 0;
      notifyListeners();
    } catch (_) {}
  }
}
