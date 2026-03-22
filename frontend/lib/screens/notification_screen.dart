import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/notification_provider.dart';
import 'package:intl/intl.dart';

class NotificationScreen extends StatefulWidget {
  const NotificationScreen({super.key});

  @override
  State<NotificationScreen> createState() => _NotificationScreenState();
}

class _NotificationScreenState extends State<NotificationScreen> {
  @override
  void initState() {
    super.initState();
    context.read<NotificationProvider>().fetchNotifications();
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<NotificationProvider>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Notifications'),
        actions: [
          if (provider.notifications.any((n) => !n.isRead))
            TextButton(
              onPressed: () => provider.markAllAsRead(),
              child: const Text('Mark all read'),
            ),
        ],
      ),
      body: provider.isLoading
          ? const Center(child: CircularProgressIndicator())
          : provider.notifications.isEmpty
          ? const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.notifications_none, size: 64, color: Colors.grey),
                  SizedBox(height: 16),
                  Text(
                    'No notifications yet',
                    style: TextStyle(color: Colors.grey, fontSize: 16),
                  ),
                ],
              ),
            )
          : RefreshIndicator(
              onRefresh: () => provider.fetchNotifications(),
              child: ListView.builder(
                itemCount: provider.notifications.length,
                itemBuilder: (context, index) {
                  final notification = provider.notifications[index];
                  return _NotificationTile(
                    notification: notification,
                    onTap: () {
                      if (!notification.isRead) {
                        provider.markAsRead(notification.id);
                      }
                    },
                  );
                },
              ),
            ),
    );
  }
}

class _NotificationTile extends StatelessWidget {
  final dynamic notification;
  final VoidCallback onTap;

  const _NotificationTile({required this.notification, required this.onTap});

  IconData _iconForType(String type) {
    switch (type) {
      case 'charging':
        return Icons.ev_station;
      case 'payment':
        return Icons.payment;
      case 'subscription':
        return Icons.star;
      case 'wallet':
        return Icons.account_balance_wallet;
      default:
        return Icons.notifications;
    }
  }

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: CircleAvatar(
        backgroundColor: notification.isRead
            ? Colors.grey.shade200
            : Colors.green.shade100,
        child: Icon(
          _iconForType(notification.type),
          color: notification.isRead ? Colors.grey : Colors.green.shade700,
        ),
      ),
      title: Text(
        notification.title,
        style: TextStyle(
          fontWeight: notification.isRead ? FontWeight.normal : FontWeight.bold,
        ),
      ),
      subtitle: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(notification.body, maxLines: 2, overflow: TextOverflow.ellipsis),
          const SizedBox(height: 4),
          Text(
            DateFormat('MMM dd, hh:mm a').format(notification.createdAt),
            style: Theme.of(context).textTheme.bodySmall,
          ),
        ],
      ),
      isThreeLine: true,
      onTap: onTap,
      tileColor: notification.isRead ? null : Colors.green.shade50,
    );
  }
}
